<?php declare(strict_types=1);

namespace Somnambulist\ReadModels\Contracts;

/**
 * Interface AttributeCaster
 *
 * @package    Somnambulist\ReadModels\Contracts
 * @subpackage Somnambulist\ReadModels\Contracts\AttributeCaster
 */
interface AttributeCaster
{

    /**
     * An array of the type names that this caster will respond to
     *
     * @return array
     */
    public function types(): array;

    public function supports(string $type): bool;

    /**
     * Cast attributes to a particular type / object resetting the attribute value
     *
     * @param array  $attributes
     * @param string $attribute
     * @param string $type
     */
    public function cast(array &$attributes, string $attribute, string $type): void;
}
