<?php declare(strict_types=1);

namespace Somnambulist\ReadModels\Relationships;

use Somnambulist\Collection\MutableCollection as Collection;
use Somnambulist\ReadModels\Manager;
use Somnambulist\ReadModels\Model;
use Somnambulist\ReadModels\ModelBuilder;
use function get_class;
use function is_null;

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
    protected bool $nullOnNotFound;

    public function __construct(ModelBuilder $query, Model $child, string $foreignKey, string $ownerKey, bool $nullOnNotFound = true)
    {
        $this->foreignKey     = $foreignKey;
        $this->ownerKey       = $ownerKey;
        $this->nullOnNotFound = $nullOnNotFound;

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
            $map = Manager::instance()->map();

            // it is entirely possible that there is no inverse relationship if it's between non-foreign key fields
            if (null !== $parent = $map->get($parentClass = get_class($this->related), $child->getRawAttribute($this->foreignKey))) {
                $map->registerRelationship($parentClass, $parent->getPrimaryKey(), get_class($child), $child->getPrimaryKey());
            }

            if (false === $this->nullOnNotFound && is_null($parent)) {
                $parent = $this->related->new();
            }

            $child->setRelationshipValue($relationship, $parent);
        });

        return $this;
    }
}
