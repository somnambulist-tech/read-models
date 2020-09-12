<?php declare(strict_types=1);

namespace Somnambulist\Components\ReadModels\Relationships;

use Somnambulist\Collection\Contracts\Collection;
use Somnambulist\Components\ReadModels\Manager;
use Somnambulist\Components\ReadModels\Model;
use Somnambulist\Components\ReadModels\ModelBuilder;
use function get_class;

/**
 * Class HasMany
 *
 * @package    Somnambulist\Components\ReadModels\Relationships
 * @subpackage Somnambulist\Components\ReadModels\Relationships\HasMany
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

            $model->setRelationshipValue($relationship, $this->related->getCollection($entities));
        });

        return $this;
    }
}
