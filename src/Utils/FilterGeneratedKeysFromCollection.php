<?php declare(strict_types=1);

namespace Somnambulist\Components\ReadModels\Utils;

use Somnambulist\Components\Collection\MutableCollection as Collection;
use Somnambulist\Components\ReadModels\Relationships\AbstractRelationship;
use function is_string;
use function Symfony\Component\String\u;

final class FilterGeneratedKeysFromCollection
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
                        is_string($key) && u($key)->containsAny([AbstractRelationship::INTERNAL_KEY_PREFIX])
                        ||
                        (
                            is_string($value) && u($value)->containsAny([
                                AbstractRelationship::RELATIONSHIP_SOURCE_MODEL_REF,
                                AbstractRelationship::RELATIONSHIP_TARGET_MODEL_REF,
                            ])
                        )
                    ;

                    return !$ignorable;
                })
                ->toArray()
            ;
    }
}
