<?php

declare(strict_types=1);

namespace Somnambulist\ReadModels\Relationships;

use function get_class;
use Somnambulist\Collection\MutableCollection as Collection;
use Somnambulist\ReadModels\Model;
use Somnambulist\ReadModels\ModelIdentityMap;
use Somnambulist\ReadModels\Utils\ClassHelpers;

/**
 * Class HasOne
 *
 * @package    Somnambulist\ReadModels\Relationships
 * @subpackage Somnambulist\ReadModels\Relationships\HasOne
 */
class HasOne extends HasOneOrMany
{

    /**
     * @param Collection $models
     * @param string     $relationship
     *
     * @return AbstractRelationship
     * @internal
     */
    public function addEagerLoadingResults(Collection $models, string $relationship): AbstractRelationship
    {
        if (count($this->getQueryBuilder()->getQueryPart('select')) > 0 && !$this->hasSelectExpression($this->foreignKey)) {
            $this->query->select($this->foreignKey);
        }

        $this->fetch();

        $map = ModelIdentityMap::instance();

        $models->each(function (Model $parent) use ($relationship, $map) {
            $ids = $map->getRelatedIdentitiesFor($parent, $class = get_class($this->related));

            $children = $map->all($class, $ids)[0] ?? null;

            ClassHelpers::setPropertyArrayKey($parent, 'relationships', $relationship, $children, Model::class);
        });

        return $this;
    }
}
