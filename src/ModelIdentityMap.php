<?php

declare(strict_types=1);

namespace Somnambulist\ReadModels;

use IlluminateAgnostic\Str\Support\Str;
use function array_intersect_key;
use function explode;
use function get_class;

/**
 * Class ModelIdentityMap
 *
 * @package    Somnambulist\ReadModels
 * @subpackage Somnambulist\ReadModels\ModelIdentityMap
 */
class ModelIdentityMap
{

    /**
     * Tracks instantiated objects by class and identity
     *
     * @var array
     */
    private $identityMap = [];

    /**
     * Tracks foreign key alias to model class for lookups
     *
     * @var array
     */
    private $aliases = [];

    /**
     * Tracks object to object relationships by class and identity
     *
     * @var array
     */
    private $relationships = [];

    /**
     * Constructor.
     */
    public function __construct()
    {

    }

    public function register(Model $model): void
    {
        if (!isset($this->aliases[$model->getForeignKey()])) {
            $this->aliases[$model->getForeignKey()] = get_class($model);
        }
    }

    public function registerRelationships(Model $model, array &$attributes)
    {
        foreach ($attributes as $key => $value) {
            $ref = null;

            if (Str::startsWith($key, Model::RELATIONSHIP_SOURCE_MODEL_REF)) {
                $ref = array_values(array_slice(explode('__', $key), -1))[0];
                unset($attributes[$key]);
            } elseif ($model->getForeignKey() === $key) {
                $ref = $key;
            }

            if ($ref) {
                // avoid needing to do array checks because the id can only be there once
                $this->relationships[$this->aliases[$ref]][$value][get_class($model)][$attributes[$model->getPrimaryKeyName()]] = 1;
            }
        }
    }

    public function relatedFor(Model $model, ?string $related = null): array
    {
        if ($related) {
            return $this->relationships[get_class($model)][$model->getPrimaryKey()][$related] ?? [];
        }

        return $this->relationships[get_class($model)][$model->getPrimaryKey()] ?? [];
    }

    public function add(Model $model): void
    {
        $class = get_class($model);

        if (!$this->has($class, $id = $model->getPrimaryKey())) {
            $this->identityMap[$class][$id] = $model;
        }
    }

    public function get($class, $id): ?object
    {
        if ($this->has($class, $id)) {
            return $this->identityMap[$class][$id];
        }

        return null;
    }

    public function all($class, array $ids)
    {
        return array_values(array_intersect_key($this->identityMap[$class], $ids));
    }

    public function has($class, $identifier): bool
    {
        return isset($this->identityMap[$class][$identifier]);
    }

    /**
     * @return int
     */
    public function count()
    {
        return array_sum(array_map('count', $this->identityMap));
    }

    /**
     * @return array
     */
    public function getIdentityMap(): array
    {
        return $this->identityMap;
    }
}
