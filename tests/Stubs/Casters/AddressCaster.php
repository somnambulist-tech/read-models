<?php declare(strict_types=1);

namespace Somnambulist\Components\ReadModels\Tests\Stubs\Casters;

use Somnambulist\Components\AttributeModel\Contracts\AttributeCasterInterface;
use Somnambulist\Components\ReadModels\Tests\Stubs\Models\Address;

use function in_array;

class AddressCaster implements AttributeCasterInterface
{
    public function types(): array
    {
        return ['address', Address::class];
    }

    public function supports(string $type): bool
    {
        return in_array($type, $this->types());
    }

    public function cast(array &$attributes, $attribute, string $type): void
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
