<?php declare(strict_types=1);

namespace Somnambulist\Components\ReadModels\Utils;

use IlluminateAgnostic\Str\Support\Str;
use Somnambulist\Components\ReadModels\Contracts\Queryable;
use function array_merge;
use function count;
use function explode;
use function implode;
use function is_numeric;

/**
 * Encapsulates the logic for generating eager loaded relationships.
 *
 * Based on the eager loading strategy deployed in Laravel Eloquent.
 * @link https://github.com/laravel/framework/blob/5.8/src/Illuminate/Database/Eloquent/Builder.php
 */
final class GenerateRelationshipsToEagerLoad
{
    /**
     * Set the relationships that should be eager loaded
     *
     * @param array $toEagerLoad The default eager loads defined on the model
     * @param mixed $relations   Strings of relationship names
     *
     * @return array
     */
    public function __invoke(array $toEagerLoad = [], ...$relations): array
    {
        if (count($relations) > 0) {
            $eagerLoad = $this->parseWithRelationships($relations);

            return array_merge($toEagerLoad, $eagerLoad);
        }

        return [];
    }

    /**
     * Parse a list of relationships into the relationship and a constraint handler
     *
     * @param array $relations
     *
     * @return array
     */
    private function parseWithRelationships(array $relations): array
    {
        $results = [];

        foreach ($relations as $name => $constraints) {
            // If the "name" value is a numeric key, we can assume that no
            // constraints have been specified. We'll just put an empty
            // Closure there, so that we can treat them all the same.
            if (is_numeric($name)) {
                $name = $constraints;

                [$name, $constraints] = Str::contains($name, ':')
                    ? $this->createSelectWithConstraint($name)
                    : [
                        $name, function () {
                            //
                        },
                    ];
            }

            // We need to separate out any nested includes, which allows the developers
            // to load deep relationships using "dots" without stating each level of
            // the relationship with its own key in the array of eager-load names.
            $results = $this->addNestedWiths($name, $results);

            $results[$name] = $constraints;
        }

        return $results;
    }

    private function createSelectWithConstraint(string $name): array
    {
        return [
            explode(':', $name)[0], function (Queryable $query) use ($name) {
                $query->select(...explode(',', explode(':', $name)[1]));
            },
        ];
    }

    /**
     * Parse the nested relationships from a dot.notation relationship
     *
     * @param string $name
     * @param array  $results
     *
     * @return array
     */
    private function addNestedWiths(string $name, array $results): array
    {
        $progress = [];

        // If the relation has already been set on the result array, we will not set it
        // again, since that would override any constraints that were already placed
        // on the relationships. We will only set the ones that are not specified.
        foreach (explode('.', $name) as $segment) {
            $progress[] = $segment;

            if (!isset($results[$last = implode('.', $progress)])) {
                $results[$last] = function () {
                    //
                };
            }
        }

        return $results;
    }
}
