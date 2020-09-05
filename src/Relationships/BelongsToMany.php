<?php declare(strict_types=1);

namespace Somnambulist\ReadModels\Relationships;

use Somnambulist\Collection\MutableCollection as Collection;
use Somnambulist\ReadModels\Manager;
use Somnambulist\ReadModels\Model;
use Somnambulist\ReadModels\ModelBuilder;
use function get_class;
use function sprintf;
use function str_replace;

/**
 * Class BelongsToMany
 *
 * @package    Somnambulist\ReadModels\Relationships
 * @subpackage Somnambulist\ReadModels\Relationships\BelongsToMany
 */
class BelongsToMany extends AbstractRelationship
{

    private string $joinTable;
    private string $joinTableSourceKey;
    private string $joinTableTargetKey;
    private string $sourceKey;
    private string $targetKey;

    public function __construct(ModelBuilder $query, Model $parent, string $joinTable, string $joinTableSourceKey, string $joinTableTargetKey, string $sourceKey, string $targetKey)
    {
        $this->joinTable          = $joinTable;
        $this->joinTableSourceKey = $joinTableSourceKey;
        $this->joinTableTargetKey = $joinTableTargetKey;
        $this->sourceKey          = $sourceKey;
        $this->targetKey          = $targetKey;

        parent::__construct($query, $parent);
    }

    public function addConstraints(Collection $models): AbstractRelationship
    {
        $this
            ->select(sprintf('%s AS %s', $this->getQualifiedSourceKeyName(), $this->getRelationshipSourceModelReferenceKeyName()))
            ->innerJoin(
                $this->related->meta()->tableAlias(),
                $this->joinTable,
                '',
                sprintf('%s = %s', $this->getQualifiedTargetKeyName(), $this->related->meta()->primaryKeyNameWithAlias())
            )
            ->whereIn(
                $this->getQualifiedSourceKeyName(), $models->extract($this->sourceKey)->unique()->toArray()
            )
        ;

        return $this;
    }

    public function addRelationshipResultsToModels(Collection $models, string $relationship): AbstractRelationship
    {
        $this->fetch();

        $map = Manager::instance()->map();

        $models->each(function (Model $model) use ($relationship, $map) {
            $ids = $map->getRelatedIdentitiesFor($model, $class = get_class($this->related));

            $entities = $map->all($class, $ids);

            $model->setRelationshipValue($relationship, new Collection($entities));
        });

        return $this;
    }

    protected function getRelationshipSourceModelReferenceKeyName(): string
    {
        return sprintf('%s__%s__%s', self::RELATIONSHIP_SOURCE_MODEL_REF, str_replace('.', '_', $this->joinTable), $this->joinTableSourceKey);
    }

    protected function getQualifiedSourceKeyName(): string
    {
        return sprintf('%s.%s', $this->joinTable, $this->joinTableSourceKey);
    }

    protected function getQualifiedTargetKeyName(): string
    {
        return sprintf('%s.%s', $this->joinTable, $this->joinTableTargetKey);
    }
}
