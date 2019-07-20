<?php

declare(strict_types=1);

namespace Somnambulist\ReadModels;

use Doctrine\Common\Inflector\Inflector;
use function explode;
use IlluminateAgnostic\Str\Support\Arr;
use IlluminateAgnostic\Str\Support\Str;
use Somnambulist\ReadModels\Utils\ClassHelpers;
use function sprintf;
use function stripos;

/**
 * Class ModelMetadata
 *
 * @package    Somnambulist\ReadModels
 * @subpackage Somnambulist\ReadModels\ModelMetadata
 */
final class ModelMetadata
{

    /**
     * @var Model
     */
    private $model;

    /**
     * @var string
     */
    private $table;

    /**
     * @var string|null
     */
    private $tableAlias;

    /**
     * @var string
     */
    private $primaryKey = 'id';

    /**
     * @var string|null
     */
    private $externalKey = null;

    /**
     * @var string|null
     */
    private $foreignKey = null;

    /**
     * Constructor.
     *
     * @param Model       $model
     * @param string      $primaryKey
     * @param string|null $table
     * @param string|null $alias
     * @param string|null $externalKey
     * @param string|null $foreignKey
     */
    public function __construct(
        Model $model,
        string $primaryKey = 'id',
        ?string $table = null,
        ?string $alias = null,
        ?string $externalKey = null,
        ?string $foreignKey = null
    )
    {
        $this->model       = $model;
        $this->table       = $table;
        $this->tableAlias  = $alias;
        $this->primaryKey  = $primaryKey;
        $this->externalKey = $externalKey;
        $this->foreignKey  = $foreignKey;
    }

    public function prefixAlias(string $column): string
    {
        if (false !== stripos($column, '.')) {
            return $column;
        }

        return sprintf('%s.%s', $this->tableAlias(), $column);
    }

    public function removeAlias(string $key): string
    {
        return stripos($key, '.') !== false ? Arr::last(explode('.', $key)) : $key;
    }

    public function table(): string
    {
        return $this->table ?? Inflector::tableize(Inflector::pluralize(ClassHelpers::getObjectShortClassName($this->model)));
    }

    public function tableAlias(): string
    {
        return $this->tableAlias ?? $this->table();
    }

    public function primaryKeyName(): string
    {
        return $this->primaryKey;
    }

    public function primaryKeyNameWithAlias(): string
    {
        return $this->prefixAlias($this->primaryKeyName());
    }

    public function externalKeyName(): ?string
    {
        return $this->externalKey;
    }

    /**
     * Creates a foreign key name from the current class name and primary key name
     *
     * This is used in relationships if a specific foreign key column name is not
     * defined on the relationship.
     *
     * @return string
     */
    public function foreignKey(): string
    {
        return $this->foreignKey ??
           sprintf('%s_%s', Str::snake(ClassHelpers::getObjectShortClassName($this->model), '_'), $this->primaryKeyName())
        ;
    }
}
