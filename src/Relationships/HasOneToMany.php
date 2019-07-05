<?php

declare(strict_types=1);

namespace Somnambulist\ReadModels\Relationships;

use Somnambulist\Collection\Collection;
use Somnambulist\ReadModels\Builder;
use Somnambulist\ReadModels\Model;
use Somnambulist\ReadModels\Utils\ClassHelpers;

/**
 * Class HasMany
 *
 * @package    Somnambulist\ReadModels\Relationships
 * @subpackage Somnambulist\ReadModels\Relationships\HasMany
 */
class HasOneToMany extends HasOneOrMany
{

    /**
     * @var string|null
     */
    protected $indexBy;

    /**
     * Constructor.
     *
     * @param Builder     $builder
     * @param Model       $parent
     * @param string      $foreignKey
     * @param string      $localKey
     * @param string|null $indexBy
     */
    public function __construct(Builder $builder, Model $parent, string $foreignKey, string $localKey, ?string $indexBy = null)
    {
        $this->indexBy    = $indexBy;

        parent::__construct($builder, $parent, $foreignKey, $localKey);
    }

    public function addEagerLoadingResults(Collection $models, string $relationship): AbstractRelationship
    {
        $relationships = [];

        $this->fetch()->each(function (Model $model) use (&$relationships) {
            if ($this->indexBy) {
                $relationships[$model->{$model->removeTableAliasFrom($this->foreignKey)}][$model->getAttribute($this->indexBy)] = $model;
            } else {
                $relationships[$model->{$model->removeTableAliasFrom($this->foreignKey)}][] = $model;
            }
        });

        $models->each(function (Model $model) use ($relationship, $relationships) {
            ClassHelpers::setPropertyArrayKey(
                $model, 'relationships', $relationship, new Collection($relationships[$model->getPrimaryKey()] ?? []), Model::class
            );
        });

        return $this;
    }
}
