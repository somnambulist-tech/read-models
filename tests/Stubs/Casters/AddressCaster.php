<?php declare(strict_types=1);

namespace Somnambulist\ReadModels\Tests\Stubs\Casters;

use Somnambulist\ReadModels\Contracts\AttributeCaster;
use Somnambulist\ReadModels\Tests\Stubs\Models\Address;
use function in_array;

/**
 * Class AddressCaster
 *
 * @package    Somnambulist\ReadModels\Tests\Stubs\Casters
 * @subpackage Somnambulist\ReadModels\Tests\Stubs\Casters\AddressCaster
 */
class AddressCaster implements AttributeCaster
{
    public function types(): array
    {
        return ['address', Address::class];
    }

    public function supports(string $type): bool
    {
        return in_array($type, $this->types());
    }

    public function cast(array &$attributes, string $attribute, string $type): void
    {
        $attributes['address'] = new Address(
            $attributes['address_line1'] ?? null,
            $attributes['address_line2'] ?? null,
            $attributes['address_town'] ?? null,
            $attributes['address_county'] ?? null,
            $attributes['address_postcode'] ?? null,
        );
    }
}
