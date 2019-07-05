<?php

declare(strict_types=1);

namespace Somnambulist\ReadModels\Relationships;

use Somnambulist\Collection\Collection;
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
