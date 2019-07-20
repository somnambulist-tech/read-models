<?php

declare(strict_types=1);

namespace Somnambulist\ReadModels\Relationships;

use Somnambulist\Collection\MutableCollection as Collection;
use Somnambulist\ReadModels\Model;
use Somnambulist\ReadModels\ModelBuilder;
use Somnambulist\ReadModels\ModelIdentityMap;
use Somnambulist\ReadModels\Utils\ClassHelpers;
use function get_class;

/**
 * Class BelongsToMany
 *
 * @package    Somnambulist\ReadModels\Relationships
 * @subpackage Somnambulist\ReadModels\Relationships\BelongsToMany
 */
class BelongsToMany extends AbstractRelationship
{

    /**
     * @var string
     */
    private $joinTable;

    /**
     * @var string
     */
    private $joinTableSourceKey;

    /**
     * @var string
     */
    private $joinTableTargetKey;

    /**
     * @var string
     */
    private $sourceKey;

    /**
     * @var string
     */
    private $targetKey;

    protected $hasMany = true;

    /**
     * Constructor.
     *
     * @param ModelBuilder $query
     * @param Model        $parent
     * @param string       $joinTable          The name of the table linking the models
     * @param string       $joinTableSourceKey The name of the column on the join table for the source
     * @param string       $joinTableTargetKey The name of the column
     * @param string       $sourceKey          The name of the attribute on the source referenced in the join table
     * @param string       $targetKey          The name of the attribute on the target referenced in the join table
     */
    public function __construct(ModelBuilder $query, Model $parent, string $joinTable, string $joinTableSourceKey, string $joinTableTargetKey, string $sourceKey, string $targetKey)
    {
        $this->joinTable          = $joinTable;
        $this->joinTableSourceKey = $joinTableSourceKey;
        $this->joinTableTargetKey = $joinTableTargetKey;
        $this->sourceKey          = $sourceKey;
        $this->targetKey          = $targetKey;

        parent::__construct($query, $parent);
    }

    protected function appendJoinCondition(): ModelBuilder
    {
        $condition = $this->expression()->eq($this->getQualifiedTargetKeyName(), $this->related->meta()->primaryKeyNameWithAlias());

        $this->query->innerJoin($this->related->meta()->tableAlias(), $this->joinTable, '', $condition);

        return $this->query;
    }

    protected function initialiseRelationship(): void
    {
        $this
            ->appendJoinCondition()
            ->whereColumn($this->getQualifiedSourceKeyName(), '=', $this->parent->getRawAttribute($this->sourceKey))
        ;
    }

    public function addEagerLoadingConstraints(Collection $models): AbstractRelationship
    {
        $this->query = $this->query->newQuery();
        $this
            ->appendJoinCondition()
            ->select(sprintf('%s AS %s', $this->getQualifiedSourceKeyName(), $this->getRelationshipSourceModelReferenceKeyName()))
            ->whereIn(
                $this->getQualifiedSourceKeyName(), $models->extract($this->sourceKey)->unique()->toArray()
            )
        ;

        return $this;
    }

    public function addEagerLoadingResults(Collection $models, string $relationship): AbstractRelationship
    {
        $this->fetch();

        $map = ModelIdentityMap::instance();

        $models->each(function (Model $model) use ($relationship, $map) {
            $ids = $map->getRelatedIdentitiesFor($model, $class = get_class($this->related));

            $entities = $map->all($class, $ids);

            ClassHelpers::setPropertyArrayKey(
                $model, 'relationships', $relationship, new Collection($entities), Model::class
            );
        });

        return $this;
    }

    protected function getRelationshipSourceModelReferenceKeyName(): string
    {
        return sprintf('%s__%s__%s', Model::RELATIONSHIP_SOURCE_MODEL_REF, $this->joinTable, $this->joinTableSourceKey);
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
