<?php

declare(strict_types=1);

namespace Somnambulist\ReadModels\Relationships;

use function get_class;
use Somnambulist\Collection\MutableCollection as Collection;
use Somnambulist\ReadModels\Builder;
use Somnambulist\ReadModels\Model;
use Somnambulist\ReadModels\Utils\ClassHelpers;

/**
 * Class HasMany
 *
 * @package    Somnambulist\ReadModels\Relationships
 * @subpackage Somnambulist\ReadModels\Relationships\HasMany
 */
class HasOneToMany extends HasOneOrMany
{

    /**
     * @var string|null
     */
    protected $indexBy;

    protected $hasMany = true;

    /**
     * Constructor.
     *
     * @param Builder     $builder
     * @param Model       $parent
     * @param string      $foreignKey
     * @param string      $localKey
     * @param string|null $indexBy
     */
    public function __construct(Builder $builder, Model $parent, string $foreignKey, string $localKey, ?string $indexBy = null)
    {
        $this->indexBy = $indexBy;

        parent::__construct($builder, $parent, $foreignKey, $localKey);
    }

    public function addEagerLoadingResults(Collection $models, string $relationship): AbstractRelationship
    {
        if (count($this->getQueryBuilder()->getQueryPart('select')) > 0 && !$this->hasSelectExpression($this->foreignKey)) {
            $this->query->select($this->foreignKey);
        }

        $this->fetch();

        $models->each(function (Model $model) use ($relationship) {
            $ids = $this->getIdentityMap()->getRelatedIdentitiesFor($model, $class = get_class($this->related));

            $entities = $this->getIdentityMap()->all($class, $ids);

            if ($this->indexBy) {
                foreach ($entities as $key => $value) {
                    $entities[$value->{$this->indexBy}] = $value;
                    unset($entities[$key]);
                }
            }

            ClassHelpers::setPropertyArrayKey(
                $model, 'relationships', $relationship, new Collection($entities), Model::class
            );
        });

        return $this;
    }
}
