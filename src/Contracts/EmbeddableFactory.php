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
     * Attributes is the array of raw key -> value pairs from the model fetch.
     * Class should be a valid classname or a static method call containing ::
     * e.g. App\MyObject::create.
     *
     * The args array can contain the named attributes needed, and if prefixed with
     * a ? can optional (null - false it not considered optional). If the arg is
     * an array this again defines the class, an array of args and finally true to
     * remove the attribute from the attributes array once replaced. The default
     * is false, so if not provided, attributes are kept. This can be useful to
     * hide the raw values so only the embeddable is accessed for data.
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
