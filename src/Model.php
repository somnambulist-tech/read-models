<?php declare(strict_types=1);

namespace Somnambulist\ReadModels;

use IlluminateAgnostic\Str\Support\Str;
use JsonSerializable;
use LogicException;
use Somnambulist\Collection\Contracts\Arrayable;
use Somnambulist\Collection\Contracts\Jsonable;
use Somnambulist\Collection\MutableCollection as Collection;
use Somnambulist\ReadModels\Exceptions\EntityNotFoundException;
use Somnambulist\ReadModels\Relationships\AbstractRelationship;
use Somnambulist\ReadModels\Relationships\BelongsTo;
use Somnambulist\ReadModels\Relationships\BelongsToMany;
use Somnambulist\ReadModels\Relationships\HasOne;
use Somnambulist\ReadModels\Relationships\HasOneToMany;
use Somnambulist\ReadModels\Utils\ClassHelpers;
use Somnambulist\ReadModels\Utils\FilterGeneratedKeysFromCollection;
use function array_key_exists;
use function is_null;
use function method_exists;
use function sprintf;

/**
 * Class Model
 *
 * @package    Somnambulist\ReadModels
 * @subpackage Somnambulist\ReadModels\Model
 */
abstract class Model extends AbstractModel implements Arrayable, Jsonable, JsonSerializable
{

    /**
     * The table associated with the model, will be guessed if not set
     *
     * Override to set a specific table if it does not match the class name.
     */
    protected string $table;

    /**
     * A default table alias to automatically scope table/columns
     */
    protected ?string $tableAlias = null;

    /**
     * The primary key for the model
     *
     * This is the primary identifier used by the database; it is not necessarily what
     * you would expose. For example: when using a relational database, it is more
     * efficient to use auto-increment / sequence integers than UUIDs as keys; but you
     * want the UUID as the external value. To support the database mappings though
     * this needs to be set to the _internal_ identifier. This way related models can
     * be set using the integer keys.
     */
    protected string $primaryKey = 'id';

    /**
     * The primary key used for external references
     *
     * Where the {@see Model::$primaryKey} is the internal database key, this is the
     * key that is used outside of the scope of the database i.e. the actual entity
     * key. Typically this will be a UUID or GUID - a globally unique identifier that
     * can be used across databases, APIs etc.
     *
     * By default this is not set, however if a column name is used, then the model
     * will be registered in the identity map with this key, along with the primary
     * key, allowing reverse lookups by UUID as well as the table id key.
     *
     * This allows in-direct connections between aggregate roots that otherwise could
     * not be linked. For example: using the Users UUID as the foreign key in a
     * separate Blog table instead of the internal integer ID.
     *
     * Typically this would be used on a BelongsTo relationship. See the example in the
     * tests of a User having a profile that is linked by UUID.
     */
    protected ?string $externalPrimaryKey = null;

    /**
     * How the primary key appears as a foreign key
     *
     * If not set, the short classname will be used, converted to snake_case and the
     * primary key appended. For example: User -> user_id. As this library is intended
     * to be used as a read-only view-model, the model class might be: UserReadModel or
     * UserView that would lead to: user_read_model_id or user_view_id. This property
     * can be set to override the builder logic to use: user_id or another variant.
     *
     * Note: this applies to 1:m and 1:1 and 1:m reversed relationships. m:m uses the
     * keys defined on the relationship to build the relationship keys.
     */
    protected ?string $foreignKey = null;

    /**
     * The relationships to eager load on every query
     */
    protected array $with = [];

    /**
     * Convert to a PHP type based on the registered types
     *
     * Additional types include complex object casters can be registered in the {@see AttributeCaster}.
     * For complex objects, the caster may remove attributes if they should not be left available from
     * the attribute array.
     *
     * <code>
     * [
     *     'uuid' => 'uuid',
     *     'location' => 'resource:geometry',
     *     'created_at' => 'datetime',
     *     'updated_at' => 'datetime',
     * ]
     * </code>
     */
    protected array $casts = [];

    /**
     * Set what can be exported, or not by attribute and relationship name
     *
     * By default ALL attributes are exported; to export specific attributes, set
     * them in the attributes array.
     *
     * <code>
     * [
     *     'attributes' => ['uuid' => 'id', 'name', 'slug', 'url'],
     *     'relationships' => ['addresses', 'contacts', 'roles:name,auth'],
     * ]
     * </code
     *
     * Contrary: by default NO relationships are exported. You must explicitly set
     * which ones to export.
     *
     * These can be overridden before calling toArray/toJson on the exporter but will
     * be used when calling jsonSerialize().
     */
    protected array $exports = [
        'attributes'    => [],
        'relationships' => [],
    ];

    /**
     * @internal
     */
    private array $relationships = [];

    /**
     * @internal
     */
    private ?ModelExporter $exporter = null;

    /**
     * @internal
     */
    private ?ModelMetadata $metadata = null;

    /**
     * The field name that flags the owner record; used by identity map
     *
     * @internal
     */
    private ?string $owningKey = null;

    public function __construct(array $attributes = [])
    {
        parent::__construct(Manager::instance()->caster()->cast($attributes, $this->casts));
    }

    public function __toString()
    {
        return $this->export()->toJson();
    }

    /**
     * @param string $id
     *
     * @return Model|null
     */
    public static function find($id): ?Model
    {
        return static::query()->find($id);
    }

    /**
     * @param string $id
     *
     * @return Model
     * @throws EntityNotFoundException
     */
    public static function findOrFail($id): Model
    {
        return static::query()->findOrFail($id);
    }

    /**
     * Eager load the specified relationships on this model
     *
     * Allows dot notation to load related.related objects.
     *
     * @param string ...$relations
     *
     * @return ModelBuilder
     */
    public static function with(...$relations): ModelBuilder
    {
        return static::query()->with(...$relations);
    }

    /**
     * Starts a new query builder process without any constraints
     *
     * @return ModelBuilder
     */
    public static function query(): ModelBuilder
    {
        return (new static)->newQuery();
    }

    public function newQuery(): ModelBuilder
    {
        $builder = new ModelBuilder($this);
        $builder->with($this->with);

        return $builder;
    }

    public function new(array $attributes = []): Model
    {
        return new static($attributes);
    }

    public function getAttributes(): array
    {
        $attributes = parent::getAttributes();

        return (new FilterGeneratedKeysFromCollection())($attributes);
    }

    /**
     * Get the requested attribute or relationship
     *
     * If a mutator is defined (getXxxxAttribute method), the attribute will be passed
     * through that first. If the attribute does not exist a virtual accessor will be
     * checked and return if there is one.
     *
     * Finally, if the relationship exists and has not been loaded, it will be at this
     * point.
     *
     * @param string $name
     *
     * @return mixed|null
     */
    public function getAttribute(string $name)
    {
        if (null !== $attr = parent::getAttribute($name)) {
            return $attr;
        }

        return $this->getRelationshipValue($name);
    }

    public function getPrimaryKey()
    {
        return $this->attributes[$this->meta()->primaryKeyName()] ?? null;
    }

    /**
     * Could return an object e.g. Uuid or string, depending on casting
     *
     * @return mixed|null
     */
    public function getExternalPrimaryKey()
    {
        return $this->attributes[$this->meta()->externalKeyName()] ?? null;
    }

    public function meta(): ModelMetadata
    {
        if (!$this->metadata instanceof ModelMetadata) {
            $this->metadata = new ModelMetadata(
                $this, $this->table, $this->primaryKey, $this->tableAlias, $this->externalPrimaryKey, $this->foreignKey
            );
        }

        return $this->metadata;
    }

    public function export(): ModelExporter
    {
        if (!$this->exporter instanceof ModelExporter) {
            $this->exporter = new ModelExporter(
                $this, $this->exports['attributes'] ?? [], $this->exports['relationships'] ?? []
            );
        }

        return $this->exporter;
    }

    public function jsonSerialize(): array
    {
        return $this->export()->toArray();
    }

    public function toArray(): array
    {
        return $this->export()->toArray();
    }

    public function toJson(int $options = 0): string
    {
        return $this->export()->toJson($options);
    }

    /**
     * Returns the relationship definition defined by the method name
     *
     * E.g. a User model hasMany Roles, the method would be "roles()".
     *
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
     * Gets the owning side of the relationships key name
     *
     * @internal
     */
    public function getOwningKey(): ?string
    {
        return $this->owningKey;
    }

    private function getRelationshipValue(string $key)
    {
        if ($this->isRelationshipLoaded($key)) {
            return $this->relationships[$key];
        }

        if (method_exists($this, $key)) {
            return $this->getRelationshipFromMethod($key);
        }

        return null;
    }

    private function getRelationshipFromMethod(string $method)
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

        $relation
            ->addConstraints($m = new Collection([$this]))
            ->addRelationshipResultsToModels($m, $method)
        ;

        return $this->relationships[$method];
    }

    private function isRelationshipLoaded(string $key): bool
    {
        return array_key_exists($key, $this->relationships);
    }

    /**
     * Define an inverse one-to-one or many relationship
     *
     * The table in this case will be the owning side of the relationship i.e. the originator
     * of the foreign key on the specified class. For example: a User has many Addresses,
     * the address table has a key: user_id linking the address to the user. This relationship
     * finds the user from the users table where the users.id = user_addresses.user_id.
     *
     * This will only associate a single model as the inverse side, nor will it update the
     * owner with this models association.
     *
     * @param string      $class
     * @param string|null $foreignKey
     * @param string|null $ownerKey
     * @param string|null $relation       The name of the relationship (uses the method name by default)
     * @param bool        $nullOnNotFound If false, returns an empty model as the related object
     *
     * @return BelongsTo
     */
    protected function belongsTo(
        string $class, ?string $foreignKey = null, ?string $ownerKey = null, ?string $relation = null, bool $nullOnNotFound = true
    ): BelongsTo
    {
        /** @var Model $instance */
        $instance   = new $class();
        $relation   = $relation ?: ClassHelpers::getCallingMethod();
        $foreignKey = $foreignKey ?: sprintf('%s_%s', Str::snake($relation), $instance->meta()->primaryKeyName());
        $ownerKey   = $ownerKey ?: $instance->meta()->primaryKeyName();

        return new BelongsTo($instance->newQuery(), $this, $foreignKey, $ownerKey, $nullOnNotFound);
    }

    /**
     * Define a new many to many relationship
     *
     * The table is the joining table between the source and the target. The source is
     * the object at the left hand side of the relationship, the target is on the right.
     * For example: User -> Roles through a user_roles table, with user_id, role_id as
     * columns. The relationship would be defined as a User has Roles so the source is
     * user_id and the target is role_id.
     *
     * The table name must be provided and will not be guessed.
     *
     * @param string      $class
     * @param string      $table
     * @param string|null $sourceColumnName The name of the column on the source table (default is source FK)
     * @param string|null $targetColumnName The name of the column on the target table (default is target FK)
     * @param string|null $sourceKey        The source models primary key name in the attributes
     * @param string|null $targetKey        The target models primary key name in the attributes
     *
     * @return BelongsToMany
     */
    protected function belongsToMany(string $class, string $table,
        ?string $sourceColumnName = null, ?string $targetColumnName = null,
        ?string $sourceKey = null, ?string $targetKey = null
    ): BelongsToMany
    {
        /** @var Model $instance */
        $instance         = new $class();
        $sourceColumnName = $sourceColumnName ?: $this->meta()->foreignKey();
        $targetColumnName = $targetColumnName ?: $instance->meta()->foreignKey();
        $sourceKey        = $sourceKey ?: $this->meta()->primaryKeyName();
        $targetKey        = $targetKey ?: $instance->meta()->primaryKeyName();

        Manager::instance()->map()->registerAlias($this, $sourceColumnName);
        Manager::instance()->map()->registerAlias($instance, $targetColumnName);

        return new BelongsToMany(
            $instance->newQuery(), $this, $table, $sourceColumnName, $targetColumnName, $sourceKey, $targetKey
        );
    }

    /**
     * Define a one to many relationship
     *
     * Here, the parent has many children, so a User can have many addresses.
     * The foreign key is the name of the parents key in the child's table.
     * local key is the child's primary key.
     *
     * indexBy allows a column on the child to be used as the key in the returned
     * collection. Note: if this is specified, then there can be only a single
     * instance of that key returned. This would usually be used on related objects
     * with a type where, the parent can only have one of each type e.g.: a contact
     * has a "type" field for: home, office, cell etc.
     *
     * @param string      $class
     * @param string|null $foreignKey The foreign key name used on the related class
     * @param string|null $parentKey  The primary key name on the parent (default primaryKeyName)
     * @param string|null $indexBy
     *
     * @return HasOneToMany
     */
    protected function hasMany(string $class, ?string $foreignKey = null, ?string $parentKey = null, ?string $indexBy = null): HasOneToMany
    {
        $foreignKey = $foreignKey ?: $this->meta()->foreignKey();
        $parentKey  = $parentKey ?: $this->meta()->primaryKeyName();

        /** @var Model $instance */
        $instance = new $class();
        $instance->owningKey = $foreignKey;

        return new HasOneToMany(
            $instance->newQuery(), $this, $instance->meta()->tableAlias() . '.' . $foreignKey, $parentKey, $indexBy
        );
    }

    /**
     * Defines a one to one relationship
     *
     * Here the parent has only one child and the child only has that parent. If
     * multiple records end up being stored, then only the first will be loaded.
     *
     * @param string      $class
     * @param string|null $foreignKey     The foreign key name used on the related class
     * @param string|null $parentKey      The primary key name on the parent (default primaryKeyName)
     * @param bool        $nullOnNotFound If false, returns an empty model as the related object
     *
     * @return HasOne
     */
    protected function hasOne(string $class, ?string $foreignKey = null, ?string $parentKey = null, bool $nullOnNotFound = true): HasOne
    {
        $foreignKey = $foreignKey ?: $this->meta()->foreignKey();
        $parentKey  = $parentKey ?: $this->meta()->primaryKeyName();

        /** @var Model $instance */
        $instance = new $class();
        $instance->owningKey = $foreignKey;

        return new HasOne(
            $instance->newQuery(), $this, $instance->meta()->tableAlias() . '.' . $foreignKey, $parentKey, $nullOnNotFound
        );
    }
}
