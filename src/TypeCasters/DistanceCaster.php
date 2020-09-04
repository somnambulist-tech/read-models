<?php declare(strict_types=1);

namespace Somnambulist\ReadModels\TypeCasters;

use Somnambulist\Domain\Entities\Types\Measure\Distance;
use Somnambulist\Domain\Entities\Types\Measure\DistanceUnit;
use Somnambulist\ReadModels\Contracts\AttributeCaster;
use function in_array;

/**
 * Class DistanceCaster
 *
 * @package    Somnambulist\ReadModels\TypeCasters
 * @subpackage Somnambulist\ReadModels\TypeCasters\DistanceCaster
 */
final class DistanceCaster implements AttributeCaster
{

    private string $distAttribute;
    private string $unitAttribute;
    private bool $remove;

    public function __construct(string $distAttribute = 'distance_value', string $unitAttribute = 'distance_unit', bool $remove = true)
    {
        $this->distAttribute = $distAttribute;
        $this->unitAttribute = $unitAttribute;
        $this->remove        = $remove;
    }

    public function types(): array
    {
        return ['distance', Distance::class];
    }

    public function supports(string $type): bool
    {
        return in_array($type, $this->types());
    }

    public function cast(array &$attributes, string $attribute, string $type): void
    {
        if (!isset($attributes[$this->distAttribute], $attributes[$this->unitAttribute])) {
            return;
        }

        $attributes[$attribute] = new Distance(
            (float)$attributes[$this->distAttribute],
            DistanceUnit::memberByValue($attributes[$this->unitAttribute])
        );

        if ($this->remove) {
            unset($attributes[$this->distAttribute], $attributes[$this->unitAttribute]);
        }
    }
}
