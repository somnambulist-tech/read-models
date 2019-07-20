<?php

declare(strict_types=1);

namespace Somnambulist\ReadModels\Hydrators;

use Somnambulist\ReadModels\Contracts\AttributeCaster;
use Somnambulist\ReadModels\Model;

/**
 * Class NullTypeCaster
 *
 * @package    Somnambulist\ReadModels\Hydrators
 * @subpackage Somnambulist\ReadModels\Hydrators\NullTypeCaster
 */
class SimpleTypeCaster implements AttributeCaster
{

    /**
     * Only removes any table prefixes and performs no other casting
     *
     * @param Model $model
     * @param array $attributes
     * @param array $casts
     *
     * @return array
     */
    public function cast(Model $model, array $attributes = [], array $casts = []): array
    {
        foreach ($attributes as $key => $value) {
            $key = $model->meta()->removeAlias($key);

            $attributes[$key] = $value;
        }

        return $attributes;
    }
}
