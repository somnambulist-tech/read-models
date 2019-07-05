<?php

declare(strict_types=1);

namespace Somnambulist\ReadModels;

use IlluminateAgnostic\Str\Support\Str;
use Somnambulist\Collection\Collection;

/**
 * Class FilterGeneratedAttributesAndKeysFromCollection
 *
 * @package    Somnambulist\ReadModels
 * @subpackage Somnambulist\ReadModels\FilterGeneratedAttributesAndKeysFromCollection
 */
class FilterGeneratedKeysFromCollection
{

    /**
     * Filters out library generated keys from the set of attributes
     *
     * @param array|Collection $attributes
     *
     * @return array
     */
    public function __invoke($attributes): array
    {
        return
            Collection::collect($attributes)
                ->filter(function ($value, $key) {
                    $ignorable =
                        Str::contains($key, [Model::INTERNAL_KEY_PREFIX])
                        ||
                        (
                            is_string($value) && Str::contains($value, [Model::RELATIONSHIP_SOURCE_MODEL_REF, Model::RELATIONSHIP_TARGET_MODEL_REF])
                        )
                    ;

                    return !$ignorable;
                })
                ->toArray()
            ;
    }
}
