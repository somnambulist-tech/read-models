<?php declare(strict_types=1);

namespace Somnambulist\ReadModels\TypeCasters;

use Somnambulist\Domain\Entities\Types\Measure\Area;
use Somnambulist\Domain\Entities\Types\Measure\AreaUnit;
use Somnambulist\ReadModels\Contracts\AttributeCaster;
use function in_array;

/**
 * Class AreaCaster
 *
 * @package    Somnambulist\ReadModels\TypeCasters
 * @subpackage Somnambulist\ReadModels\TypeCasters\AreaCaster
 */
final class AreaCaster implements AttributeCaster
{

    private string $areaAttribute;
    private string $unitAttribute;
    private bool $remove;

    public function __construct(string $areaAttribute = 'area_value', string $unitAttribute = 'area_unit', bool $remove = true)
    {
        $this->areaAttribute = $areaAttribute;
        $this->unitAttribute = $unitAttribute;
        $this->remove        = $remove;
    }

    public function types(): array
    {
        return ['area', Area::class];
    }

    public function supports(string $type): bool
    {
        return in_array($type, $this->types());
    }

    public function cast(array &$attributes, string $attribute, string $type): void
    {
        if (!isset($attributes[$this->areaAttribute], $attributes[$this->unitAttribute])) {
            return;
        }

        $attributes[$attribute] = new Area(
            (float)$attributes[$this->areaAttribute],
            AreaUnit::memberByValue($attributes[$this->unitAttribute])
        );

        if ($this->remove) {
            unset($attributes[$this->areaAttribute], $attributes[$this->unitAttribute]);
        }
    }
}
