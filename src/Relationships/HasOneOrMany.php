<?php declare(strict_types=1);

namespace Somnambulist\Components\ReadModels\Relationships;

use Somnambulist\Components\Collection\Contracts\Collection;
use Somnambulist\Components\ReadModels\Model;
use Somnambulist\Components\ReadModels\ModelBuilder;

abstract class HasOneOrMany extends AbstractRelationship
{
    protected string $foreignKey;
    protected string $localKey;

    public function __construct(ModelBuilder $builder, Model $parent, string $foreignKey, string $localKey)
    {
        $this->foreignKey = $foreignKey;
        $this->localKey   = $localKey;

        parent::__construct($builder, $parent);
    }

    public function addConstraints(Collection $models): AbstractRelationship
    {
        $this->hasConstraints = true;

        $this->query = $this->query->whereIn(
            $this->foreignKey, $models->extract($this->localKey)->unique()->toArray()
        );

        return $this;
    }
}
