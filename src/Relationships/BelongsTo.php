<?php

declare(strict_types=1);

namespace Somnambulist\ReadModels\Relationships;

use function get_class;
use Somnambulist\Collection\MutableCollection as Collection;
use Somnambulist\ReadModels\ModelBuilder;
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
     * @param ModelBuilder $query
     * @param Model        $child
     * @param string       $foreignKey
     * @param string       $ownerKey
     */
    public function __construct(ModelBuilder $query, Model $child, string $foreignKey, string $ownerKey)
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
        if (count($this->getQueryBuilder()->getQueryPart('select')) > 0 && !$this->hasSelectExpression($this->ownerKey)) {
            $this->query->select($this->ownerKey);
        }

        $this->fetch();

        $models->each(function (Model $child) use ($relationship) {
            $map    = $this->getIdentityMap();
            $parent = null;

            // it is entirely possible that there is no inverse relationship if it's between non-foreign key fields
            if (null !== $parent = $map->get($parentClass = get_class($this->related), $child->{$this->foreignKey})) {
                $map->registerRelationship($parentClass, $parent->getPrimaryKey(), get_class($child), $child->getPrimaryKey());
            }

            ClassHelpers::setPropertyArrayKey($child, 'relationships', $relationship, $parent, Model::class);
        });

        return $this;
    }

}
