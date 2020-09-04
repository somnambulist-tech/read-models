<?php declare(strict_types=1);

namespace Somnambulist\ReadModels\Relationships;

use Somnambulist\Collection\MutableCollection as Collection;
use Somnambulist\ReadModels\Manager;
use Somnambulist\ReadModels\Model;
use Somnambulist\ReadModels\ModelBuilder;
use Somnambulist\ReadModels\Utils\ClassHelpers;
use function get_class;

/**
 * Class HasMany
 *
 * @package    Somnambulist\ReadModels\Relationships
 * @subpackage Somnambulist\ReadModels\Relationships\HasMany
 */
class HasOneToMany extends HasOneOrMany
{

    protected ?string $indexBy;

    public function __construct(ModelBuilder $builder, Model $parent, string $foreignKey, string $localKey, ?string $indexBy = null)
    {
        $this->indexBy = $indexBy;

        parent::__construct($builder, $parent, $foreignKey, $localKey);
    }

    public function fetch(): Collection
    {
        $entities = parent::fetch();

        if ($this->indexBy) {
            foreach ($entities as $key => $value) {
                /** @var Model $value */
                $entities[$value->getRawAttribute($this->indexBy)] = $value;
                unset($entities[$key]);
            }
        }

        return $entities;
    }

    public function addRelationshipResultsToModels(Collection $models, string $relationship): AbstractRelationship
    {
        if (count($this->getQueryBuilder()->getQueryPart('select')) > 0 && !$this->hasSelectExpression($this->foreignKey)) {
            $this->query->select($this->foreignKey);
        }

        $this->fetch();

        $map = Manager::instance()->map();

        $models->each(function (Model $model) use ($relationship, $map) {
            $ids = $map->getRelatedIdentitiesFor($model, $class = get_class($this->related));

            $entities = $map->all($class, $ids);

            if ($this->indexBy) {
                foreach ($entities as $key => $value) {
                    /** @var Model $value */
                    $entities[$value->getRawAttribute($this->indexBy)] = $value;
                    unset($entities[$key]);
                }
            }

            ClassHelpers::setPropertyArrayKey(
                $model, 'relationships', $relationship, new Collection($entities), Model::class
            );
        });

        return $this;
    }
}
