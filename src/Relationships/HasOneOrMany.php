<?php

declare(strict_types=1);

namespace Somnambulist\ReadModels\Relationships;

use Somnambulist\Collection\Collection;
use Somnambulist\ReadModels\Builder;
use Somnambulist\ReadModels\Model;

/**
 * Class HasOneOrMany
 *
 * @package    Somnambulist\ReadModels\Relationships
 * @subpackage Somnambulist\ReadModels\Relationships\HasOneOrMany
 */
class HasOneOrMany extends AbstractRelationship
{

    /**
     * @var string
     */
    protected $foreignKey;

    /**
     * @var string
     */
    protected $localKey;

    /**
     * Constructor.
     *
     * @param Builder $builder
     * @param Model   $parent
     * @param string  $foreignKey
     * @param string  $localKey
     */
    public function __construct(Builder $builder, Model $parent, string $foreignKey, string $localKey)
    {
        $this->foreignKey = $foreignKey;
        $this->localKey   = $localKey;

        parent::__construct($builder, $parent);
    }

    protected function initialiseRelationship(): void
    {
        $this->query->whereColumn($this->foreignKey, '=', $this->parent->{$this->localKey});
        $this->query->whereNotNull($this->foreignKey);
    }

    public function addEagerLoadingConstraints(Collection $models): void
    {
        $this->query->whereIn(
            $this->foreignKey, $models->extract($this->localKey)->unique()->toArray()
        );
    }

    /**
     * @return Collection|Model[]
     */
    public function getResults()
    {
        return $this->query->fetch();
    }
}
