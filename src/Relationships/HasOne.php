<?php declare(strict_types=1);

namespace Somnambulist\ReadModels\Relationships;

use Somnambulist\Collection\MutableCollection as Collection;
use Somnambulist\ReadModels\Manager;
use Somnambulist\ReadModels\Model;
use Somnambulist\ReadModels\Utils\ClassHelpers;
use function get_class;

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
        if (count($this->getQueryBuilder()->getQueryPart('select')) > 0 && !$this->hasSelectExpression($this->foreignKey)) {
            $this->query->select($this->foreignKey);
        }

        $this->fetch();

        $map = Manager::instance()->map();

        $models->each(function (Model $parent) use ($relationship, $map) {
            $ids = $map->getRelatedIdentitiesFor($parent, $class = get_class($this->related));

            $children = $map->all($class, $ids)[0] ?? null;

            ClassHelpers::setPropertyArrayKey($parent, 'relationships', $relationship, $children, Model::class);
        });

        return $this;
    }
}
