<?php

declare(strict_types=1);

namespace Somnambulist\ReadModels\Contracts;

use Somnambulist\ReadModels\Model;

/**
 * Interface AttributeCaster
 *
 * @package    Somnambulist\ReadModels\Contracts
 * @subpackage Somnambulist\ReadModels\Contracts\AttributeCaster
 */
interface AttributeCaster
{

    /**
     * Cast attributes to a type or simple object (or not)
     *
     * Return the modified attributes an array.
     *
     * @param Model $model
     * @param array $attributes
     * @param array $casts
     *
     * @return array
     */
    public function cast(Model $model, array $attributes = [], array $casts = []): array;
}
