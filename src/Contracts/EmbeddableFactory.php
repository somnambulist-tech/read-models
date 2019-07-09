<?php

declare(strict_types=1);

namespace Somnambulist\ReadModels\Contracts;

/**
 * Interface EmbeddableFactory
 *
 * @package    Somnambulist\ReadModels\Contracts
 * @subpackage Somnambulist\ReadModels\Contracts\EmbeddableFactory
 */
interface EmbeddableFactory
{

    /**
     * Create an object from the attributes including sub-objects
     *
     * See {@see Model::$embeds} for the syntax.
     *
     * @param array  $attributes THe source model attributes
     * @param string $class      The embeddable to make
     * @param array  $args       The names of the attributes needed for the embeddable
     * @param bool   $remove     Remove attributes after hydrating to embeddable
     *
     * @return object|null
     */
    public function make(array &$attributes, string $class, array $args, bool $remove = true): ?object;
}
