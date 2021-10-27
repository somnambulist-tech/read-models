<?php declare(strict_types=1);

namespace Somnambulist\Components\ReadModels\Utils;

use IlluminateAgnostic\Str\Support\Str;
use Somnambulist\Components\Collection\MutableCollection as Collection;
use Somnambulist\Components\ReadModels\Relationships\AbstractRelationship;
use function is_string;

/**
 * Class FilterGeneratedAttributesAndKeysFromCollection
 *
 * @package    Somnambulist\Components\ReadModels
 * @subpackage Somnambulist\Components\ReadModels\FilterGeneratedAttributesAndKeysFromCollection
 */
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
                        Str::contains($key, [AbstractRelationship::INTERNAL_KEY_PREFIX])
                        ||
                        (
                            is_string($value) && Str::contains($value, [
                                AbstractRelationship::RELATIONSHIP_SOURCE_MODEL_REF, AbstractRelationship::RELATIONSHIP_TARGET_MODEL_REF
                            ])
                        )
                    ;

                    return !$ignorable;
                })
                ->toArray()
            ;
    }
}
