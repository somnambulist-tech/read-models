<?php declare(strict_types=1);

namespace Somnambulist\ReadModels\TypeCasters;

use Somnambulist\Domain\Entities\Types\Geography\Coordinate;
use Somnambulist\Domain\Entities\Types\Geography\Srid;
use Somnambulist\ReadModels\Contracts\AttributeCaster;
use function in_array;

/**
 * Class CoordinateCaster
 *
 * @package    Somnambulist\ReadModels\TypeCasters
 * @subpackage Somnambulist\ReadModels\TypeCasters\CoordinateCaster
 */
final class CoordinateCaster implements AttributeCaster
{

    private string $latAttribute;
    private string $lngAttribute;
    private string $sridAttribute;
    private bool   $remove;

    public function __construct(string $latAttribute = 'latitude', string $lngAttribute = 'longitude', string $sridAttribute = 'srid', bool $remove = true)
    {
        $this->latAttribute  = $latAttribute;
        $this->lngAttribute  = $lngAttribute;
        $this->sridAttribute = $sridAttribute;
        $this->remove        = $remove;
    }

    public function types(): array
    {
        return ['coordinate', Coordinate::class];
    }

    public function supports(string $type): bool
    {
        return in_array($type, $this->types());
    }

    public function cast(array &$attributes, string $attribute, string $type): void
    {
        if (!isset($attributes[$this->latAttribute], $attributes[$this->lngAttribute], $attributes[$this->sridAttribute])) {
            return;
        }

        $attributes[$attribute] = new Coordinate(
            $attributes[$this->latAttribute], $attributes[$this->lngAttribute], Srid::memberByValue($attributes[$this->sridAttribute])
        );

        if ($this->remove) {
            unset($attributes[$this->lngAttribute], $attributes[$this->latAttribute], $attributes[$this->sridAttribute]);
        }
    }
}
