<?php declare(strict_types=1);

namespace Somnambulist\Components\ReadModels\Relationships;

use Somnambulist\Components\Collection\Contracts\Collection;
use Somnambulist\Components\ReadModels\Manager;
use Somnambulist\Components\ReadModels\Model;
use Somnambulist\Components\ReadModels\ModelBuilder;
use Somnambulist\Components\ReadModels\Utils\ClassHelpers;
use function get_class;
use function is_null;

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
        $this->hasConstraints = true;

        $this->query = $this->query->whereIn(
            $this->ownerKey, $models->map->getRawAttribute($this->foreignKey)->removeNulls()->unique()->toArray()
        );

        return $this;
    }

    public function addRelationshipResultsToModels(Collection $models, string $relationship): AbstractRelationship
    {
        if (ClassHelpers::countPart($this->getQueryBuilder(), 'select') > 0 && !$this->hasSelectExpression($this->ownerKey)) {
            $this->query->select($this->ownerKey);
        }
        if (count($this->getQueryBuilder()->getParameters()) > 0) {
            $this->fetch();
        }

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
