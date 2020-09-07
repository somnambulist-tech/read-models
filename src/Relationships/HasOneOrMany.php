<?php declare(strict_types=1);

namespace Somnambulist\Components\ReadModels\Relationships;

use Somnambulist\Collection\MutableCollection as Collection;
use Somnambulist\Components\ReadModels\Model;
use Somnambulist\Components\ReadModels\ModelBuilder;

/**
 * Class HasOneOrMany
 *
 * @package    Somnambulist\Components\ReadModels\Relationships
 * @subpackage Somnambulist\Components\ReadModels\Relationships\HasOneOrMany
 */
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
        $this->query = $this->query->whereIn(
            $this->foreignKey, $models->extract($this->localKey)->unique()->toArray()
        );

        return $this;
    }
}
