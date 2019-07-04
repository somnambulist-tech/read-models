<?php

declare(strict_types=1);

namespace Somnambulist\ReadModels;

use Doctrine\Common\Inflector\Inflector;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Types\Type;
use DomainException;
use IlluminateAgnostic\Str\Support\Arr;
use IlluminateAgnostic\Str\Support\Str;
use InvalidArgumentException;
use JsonSerializable;
use LogicException;
use Somnambulist\ReadModels\Relationships\AbstractRelationship;
use Somnambulist\ReadModels\Relationships\HasOneToMany;
use Somnambulist\ReadModels\Utils\ClassHelpers;
use Somnambulist\ReadModels\Utils\StrConverter;

abstract class Model implements JsonSerializable
{

    /**
     * @var array|Connection[]
     */
    protected static $connections = [];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table;

    /**
     * A default table alias to apply to automatically scope table/columns
     *
     * @var string|null
     */
    protected $tableAlias;

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * The "type" of the primary key ID.
     *
     * @var string
     */
    protected $primaryKeyType = 'int';

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = [];

    /**
     * The loaded database properties
     *
     * @var array
     */
    protected $attributes = [];

    /**
     * The various relationships between this model and others
     *
     * @var array
     */
    protected $relationships = [];

    /**
     * Convert to a PHP type based on the registered Doctrine Types
     *
     * @var array
     */
    protected $casts = [];

    /**
     * @var ModelExporter
     */
    protected $exporter;

    /**
     * Constructor.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->mapAttributes($attributes);

        $this->exporter = new ModelExporter($this);
    }

    /**
     * @param string $method
     * @param array  $parameters
     *
     * @return Model
     */
    public function __call($method, $parameters)
    {
        if ($this->attributes[Str::snake($method)]) {
            return $this->__get(Str::snake($method));
        }

        return $this->newQuery()->{$method}(...$parameters);
    }

    /**
     * Handle dynamic static method calls into the method.
     *
     * @param string $method
     * @param array  $parameters
     *
     * @return mixed
     */
    public static function __callStatic($method, $parameters)
    {
        return (new static)->$method(...$parameters);
    }

    /**
     * @param string $name
     *
     * @return mixed|AbstractRelationship|null
     */
    public function __get($name)
    {
        if (isset($this->attributes[$name])) {
            $mutator = sprintf('get%sAttribute', Str::studly($name));

            if (method_exists($this, $mutator)) {
                return $this->{$mutator}($this->attributes[$name]);
            }

            return $this->attributes[$name];
        }

        if (method_exists(self::class, $name)) {
            return null;
        }

        return $this->getRelationshipValue($name);
    }

    public function __set($name, $value)
    {
        throw new DomainException(sprintf('Models are read-only and cannot be changed once loaded'));
    }

    public function __unset($name)
    {
        throw new DomainException(sprintf('Models are read-only and cannot be changed once loaded'));
    }

    public function __isset($name)
    {
        return isset($this->attributes[$name]) || $this->getRelationshipValue($name);
    }

    public function __toString()
    {
        return $this->exporter->toJson();
    }

    /**
     * Set the DBAL Connection to use by default or for a specific model
     *
     * The model class name should be used and then that connection will be used with all
     * instances of that model. A default connection should still be provided as a fallback.
     *
     * @param Connection $connection
     * @param string     $model
     */
    public static function bindConnection(Connection $connection, string $model = 'default'): void
    {
        static::$connections[$model] = $connection;
    }

    /**
     * @param string $model
     *
     * @return Connection
     * @throws InvalidArgumentException if connection has not been setup
     */
    public static function connection(string $model = null): Connection
    {
        $try = $model ?? 'default';

        if ('default' !== $model && !isset(static::$connections[$try])) {
            $try = 'default';
        }

        if (null === $connection = (static::$connections[$try] ?? null)) {
            throw new InvalidArgumentException(
                sprintf('A connection for "%s" or "%s" has not been defined', $model, $try)
            );
        }

        return $connection;
    }

    public static function with(...$relations): Builder
    {
        return static::query()->with(...$relations);
    }

    public static function query(): Builder
    {
        return (new static)->newQuery();
    }

    /**
     * @param array $attributes
     */
    private function mapAttributes(array $attributes): void
    {
        foreach ($attributes as $key => $value) {
            $key = $this->stripTableAlias($key);

            if (null !== $type = $this->getCastType($key)) {
                if (Str::startsWith($type, 'resource:')) {
                    $value = is_null($value) ? null : StrConverter::toResource($value);
                    $type  = Str::replaceFirst('resource:', '', $type);
                }

                $value = Type::getType($type)->convertToPHPValue(
                    $value, static::connection(static::class)->getDatabasePlatform()
                );
            }

            $this->attributes[$key] = $value;
        }
    }

    /**
     * @param string $key
     *
     * @return string|null
     */
    private function getCastType(string $key): ?string
    {
        $cast = $this->casts[$key] ?? null;

        return $cast ? trim(strtolower($cast)) : $cast;
    }

    /**
     * @return Builder
     */
    public function newQuery()
    {
        return new Builder($this, static::connection(static::class)->createQueryBuilder());
    }

    /**
     * @param array $attributes
     *
     * @return Model
     */
    public function newModel(array $attributes = []): Model
    {
        return new static($attributes);
    }

    /**
     * @return array
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * @param string $column
     *
     * @return string
     */
    public function prefixTableAlias(string $column): string
    {
        if (false !== stripos($column, '.')) {
            return $column;
        }

        return sprintf('%s.%s', ($this->getTableAlias() ?: $this->getTable()), $column);
    }

    /**
     * @param string $key
     *
     * @return string
     */
    public function stripTableAlias(string $key): string
    {
        return stripos($key, '.') !== false ? Arr::last(explode('.', $key)) : $key;
    }

    /**
     * Get the table associated with the model.
     *
     * @return string
     */
    public function getTable()
    {
        return $this->table ?? Inflector::tableize(Inflector::pluralize(ClassHelpers::getObjectShortClassName($this)));
    }

    public function getTableAlias(): ?string
    {
        return $this->tableAlias;
    }

    /**
     * @return string
     */
    public function getPrimaryKey(): string
    {
        return $this->primaryKey;
    }

    /**
     * @return string
     */
    public function getPrimaryKeyType(): string
    {
        return $this->primaryKeyType;
    }

    /**
     * @return string
     */
    public function getAliasedPrimaryKey(): string
    {
        return $this->prefixTableAlias($this->getPrimaryKey());
    }

    /**
     * @return string
     */
    public function getForeignKey(): string
    {
        return Str::slug(ClassHelpers::getObjectShortClassName($this), '_') . '_' . $this->getPrimaryKey();
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return $this->exporter->toArray();
    }



    /**
     * Determine if the given relation is loaded.
     *
     * @param string $key
     *
     * @return bool
     */
    public function isRelationshipLoaded($key): bool
    {
        return array_key_exists($key, $this->relationships);
    }

    /**
     * Set the given relationship on the model.
     *
     * @param string $relation
     * @param mixed  $value
     *
     * @return $this
     */
    public function setRelationshipResults($relation, $value)
    {
        $this->relationships[$relation] = $value;

        return $this;
    }

    /**
     * @param string $method
     *
     * @return AbstractRelationship
     */
    public function getRelationship(string $method): AbstractRelationship
    {
        $relationship = $this->$method();

        if (!$relationship instanceof AbstractRelationship) {
            if (is_null($relationship)) {
                throw new LogicException(sprintf(
                    '%s::%s must return a relationship instance, but "null" was returned. Was the "return" keyword used?', static::class, $method
                ));
            }

            throw new LogicException(sprintf(
                '%s::%s must return a relationship instance.', static::class, $method
            ));
        }

        return $relationship;
    }

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function getRelationshipValue(string $key)
    {
        if ($this->isRelationshipLoaded($key)) {
            return $this->relationships[$key];
        }

        if (method_exists($this, $key)) {
            return $this->getRelationshipFromMethod($key);
        }

        return null;
    }

    /**
     * Get a relationship value from a method.
     *
     * @param string $method
     *
     * @return mixed
     *
     * @throws LogicException
     */
    protected function getRelationshipFromMethod($method)
    {
        $relation = $this->$method();

        if (!$relation instanceof AbstractRelationship) {
            if (is_null($relation)) {
                throw new LogicException(sprintf(
                    '%s::%s must return a relationship instance, but "null" was returned. Was the "return" keyword used?', static::class, $method
                ));
            }

            throw new LogicException(sprintf(
                '%s::%s must return a relationship instance.', static::class, $method
            ));
        }

        $this->setRelationshipResults($method, $results = $relation->getResults());

        return $results;
    }

    public function hasMany(string $class, string $foreignKey, string $localKey)
    {
        /** @var Model $instance */
        $instance   = new $class();
        $foreignKey = $foreignKey ?: $this->getForeignKey();
        $localKey   = $localKey ?: $this->getPrimaryKey();

        return new HasOneToMany(
            $instance->newQuery(), $this, $instance->getTable() . '.' . $foreignKey, $localKey
        );
    }
}
