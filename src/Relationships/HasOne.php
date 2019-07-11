<?php

declare(strict_types=1);

namespace Somnambulist\ReadModels\Relationships;

use Somnambulist\Collection\MutableCollection as Collection;
use Somnambulist\ReadModels\Model;
use Somnambulist\ReadModels\Utils\ClassHelpers;

/**
 * Class HasOne
 *
 * @package    Somnambulist\ReadModels\Relationships
 * @subpackage Somnambulist\ReadModels\Relationships\HasOne
 */
class HasOne extends HasOneOrMany
{

    public function addEagerLoadingResults(Collection $models, string $relationship): AbstractRelationship
    {
        $relationships = [];

        if (count($this->getQueryBuilder()->getQueryPart('select')) > 0 && !$this->hasSelectExpression($this->foreignKey)) {
            $this->query->select($this->foreignKey);
        }

        $this->fetch()->each(function (Model $model) use (&$relationships) {
            $relationships[$model->{$model->removeTableAliasFrom($this->foreignKey)}] = $model;
        });

        $models->each(function (Model $model) use ($relationship, $relationships) {
            ClassHelpers::setPropertyArrayKey(
                $model, 'relationships', $relationship, $relationships[$model->getPrimaryKey()] ?? null, Model::class
            );
        });

        return $this;
    }
}
