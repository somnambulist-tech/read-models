<?php

declare(strict_types=1);

namespace Somnambulist\ReadModels\Relationships;

use IlluminateAgnostic\Str\Support\Arr;
use Somnambulist\Collection\Collection;
use Somnambulist\ReadModels\Builder;
use Somnambulist\ReadModels\Model;

/**
 * Class Relationship
 *
 * @package    Somnambulist\ReadModels\Relationships
 * @subpackage Somnambulist\ReadModels\Relationships\Relationship
 */
abstract class AbstractRelationship
{

    /**
     * @var Builder
     */
    protected $query;

    /**
     * @var Model
     */
    protected $parent;

    /**
     * @var Model
     */
    protected $related;

    /**
     * Constructor.
     *
     * @param Builder $builder
     * @param Model   $parent
     */
    public function __construct(Builder $builder, Model $parent)
    {
        $this->parent  = $parent;
        $this->related = $builder->getModel();
        $this->query   = $builder;

        $this->initialiseRelationship();
    }

    abstract protected function initialiseRelationship(): void;

    /**
     * @param Collection $models
     */
    abstract public function addEagerLoadingConstraints(Collection $models): void;

    /**
     * Executes the relationship, returning any results
     *
     * @return mixed
     */
    abstract public function getResults();

    /**
     * @return Builder
     */
    public function getQuery(): Builder
    {
        return $this->query;
    }

    /**
     * @return Model
     */
    public function getParent(): Model
    {
        return $this->parent;
    }

    /**
     * @return Model
     */
    public function getRelated(): Model
    {
        return $this->related;
    }
}
