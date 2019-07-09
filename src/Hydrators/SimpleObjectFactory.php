<?php

declare(strict_types=1);

namespace Somnambulist\ReadModels\Hydrators;

use IlluminateAgnostic\Str\Support\Str;
use function array_key_exists;
use function call_user_func_array;
use function count;
use function is_array;
use function is_null;
use function is_scalar;
use Somnambulist\ReadModels\Contracts\EmbeddableFactory;
use Somnambulist\ReadModels\Model;

/**
 * Class SimpleObjectFactory
 *
 * @package    Somnambulist\ReadModels\Hydrators
 * @subpackage Somnambulist\ReadModels\Hydrators\SimpleObjectFactory
 */
class SimpleObjectFactory implements EmbeddableFactory
{

    /**
     * Create an object from the attributes including sub-objects
     *
     * {@see Model::$embeds} for syntax.
     *
     * @param array  $attributes THe source model attributes
     * @param string $class      The embeddable to make
     * @param array  $args       The names of the attributes needed for the embeddable
     * @param bool   $remove     Remove attributes after hydrating to embeddable
     *
     * @return object|null
     */
    public function make(array &$attributes, string $class, array $args, bool $remove = true): ?object
    {
        $params   = [];
        $toRemove = [];

        foreach ($args as $arg) {
            if (is_array($arg)) {
                $params[] = $this->make($attributes, $arg[0], $arg[1], $arg[2] ?? false);
            } elseif (is_scalar($arg)) {
                $optional   = Str::startsWith($arg, '?');
                $value      = $attributes[$arg = Str::replaceFirst('?', '', $arg)];
                $toRemove[] = $arg;

                if (!array_key_exists($arg, $attributes) || (is_null($value) && !$optional)) {
                    continue;
                }

                $params[] = $value;
            }
        }

        if ($remove) {
            foreach ($toRemove as $arg) {
                unset($attributes[$arg]);
            }
        }

        if (empty($params) || count($params) !== count($args)) {
            return null;
        }

        if (Str::contains($class, '::')) {
            return call_user_func_array($class, $params);
        }

        return new $class(...$params);
    }
}
