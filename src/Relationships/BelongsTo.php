<?php

declare(strict_types=1);

namespace Somnambulist\ReadModels\Relationships;

use Somnambulist\Collection\Collection;
use Somnambulist\ReadModels\Builder;
use Somnambulist\ReadModels\Model;
use Somnambulist\ReadModels\Utils\ClassHelpers;

/**
 * Class BelongsTo
 *
 * @package    Somnambulist\ReadModels\Relationships
 * @subpackage Somnambulist\ReadModels\Relationships\BelongsTo
 */
class BelongsTo extends AbstractRelationship
{

    /**
     * The foreign key of the parent model
     *
     * @var string
     */
    protected $foreignKey;

    /**
     * The associated key on the parent model
     *
     * @var string
     */
    protected $ownerKey;

    /**
     * Constructor.
     *
     * @param Builder $query
     * @param Model   $child
     * @param string  $foreignKey
     * @param string  $ownerKey
     */
    public function __construct(Builder $query, Model $child, string $foreignKey, string $ownerKey)
    {
        $this->foreignKey = $foreignKey;
        $this->ownerKey   = $ownerKey;

        parent::__construct($query, $child);
    }

    protected function initialiseRelationship(): void
    {
        $this->query->whereColumn($this->related->getPrimaryKeyWithTableAlias(), '=', $this->parent->{$this->foreignKey});
    }

    public function addEagerLoadingConstraints(Collection $models): AbstractRelationship
    {
        $this->query = $this->query->newQuery()->whereIn(
            $this->ownerKey, $models->extract($this->foreignKey)->unique()->toArray()
        );

        return $this;
    }

    public function addEagerLoadingResults(Collection $models, string $relationship): AbstractRelationship
    {
        $relationships = [];

        if (count($this->getQueryBuilder()->getQueryPart('select')) > 0 && !$this->hasSelectExpression($this->ownerKey)) {
            $this->query->select($this->ownerKey);
        }

        $this->fetch()->each(function (Model $model) use (&$relationships) {
            $relationships[$model->{$model->removeTableAliasFrom($this->ownerKey)}] = $model;
        });

        $models->each(function (Model $model) use ($relationship, $relationships) {
            ClassHelpers::setPropertyArrayKey(
                $model, 'relationships', $relationship, $relationships[$model->{$this->foreignKey}] ?? null, Model::class
            );
        });

        return $this;
    }

}
