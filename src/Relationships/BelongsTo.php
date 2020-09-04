<?php declare(strict_types=1);

namespace Somnambulist\ReadModels\Relationships;

use Somnambulist\Collection\MutableCollection as Collection;
use Somnambulist\ReadModels\Manager;
use Somnambulist\ReadModels\Model;
use Somnambulist\ReadModels\ModelBuilder;
use Somnambulist\ReadModels\Utils\ClassHelpers;
use function get_class;

/**
 * Class BelongsTo
 *
 * @package    Somnambulist\ReadModels\Relationships
 * @subpackage Somnambulist\ReadModels\Relationships\BelongsTo
 */
class BelongsTo extends AbstractRelationship
{

    protected string $foreignKey;
    protected string $ownerKey;

    public function __construct(ModelBuilder $query, Model $child, string $foreignKey, string $ownerKey)
    {
        $this->foreignKey = $foreignKey;
        $this->ownerKey   = $ownerKey;

        parent::__construct($query, $child);
    }

    public function addConstraints(Collection $models): AbstractRelationship
    {
        $this->query = $this->query->whereIn(
            $this->ownerKey, $models->map->getRawAttribute($this->foreignKey)->removeNulls()->unique()->toArray()
        );

        return $this;
    }

    public function addRelationshipResultsToModels(Collection $models, string $relationship): AbstractRelationship
    {
        if (count($this->getQueryBuilder()->getQueryPart('select')) > 0 && !$this->hasSelectExpression($this->ownerKey)) {
            $this->query->select($this->ownerKey);
        }

        $this->fetch();

        $models->each(function (Model $child) use ($relationship) {
            $map    = Manager::instance()->map();
            $parent = null;

            // it is entirely possible that there is no inverse relationship if it's between non-foreign key fields
            if (null !== $parent = $map->get($parentClass = get_class($this->related), $child->getRawAttribute($this->foreignKey))) {
                $map->registerRelationship($parentClass, $parent->getPrimaryKey(), get_class($child), $child->getPrimaryKey());
            }

            ClassHelpers::setPropertyArrayKey($child, 'relationships', $relationship, $parent, Model::class);
        });

        return $this;
    }
}
