<?php declare(strict_types=1);

namespace Somnambulist\ReadModels;

use IlluminateAgnostic\Str\Support\Arr;
use IlluminateAgnostic\Str\Support\Str;
use Somnambulist\ReadModels\Utils\ClassHelpers;
use function explode;
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

    private Model $model;
    private string $table;
    private string $primaryKey;
    private ?string $tableAlias;
    private ?string $externalKey;
    private ?string $foreignKey;

    public function __construct(
        Model $model,
        string $table,
        string $primaryKey = 'id',
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
        return $this->table;
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
        $key = sprintf('%s_%s', Str::snake(ClassHelpers::getObjectShortClassName($this->model), '_'), $this->primaryKeyName());

        return $this->foreignKey ?? $key;
    }
}
