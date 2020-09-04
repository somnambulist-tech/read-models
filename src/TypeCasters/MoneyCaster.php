<?php declare(strict_types=1);

namespace Somnambulist\ReadModels\TypeCasters;

use Somnambulist\Domain\Entities\Types\Money\Currency;
use Somnambulist\Domain\Entities\Types\Money\Money;
use Somnambulist\ReadModels\Contracts\AttributeCaster;
use function in_array;

/**
 * Class MoneyCaster
 *
 * @package    Somnambulist\ReadModels\TypeCasters
 * @subpackage Somnambulist\ReadModels\TypeCasters\MoneyCaster
 */
final class MoneyCaster implements AttributeCaster
{

    private string $amtAttribute;
    private string $curAttribute;
    private bool   $remove;

    public function __construct(string $amtAttribute = 'amount', string $curAttribute = 'currency', bool $remove = true)
    {
        $this->amtAttribute = $amtAttribute;
        $this->curAttribute = $curAttribute;
        $this->remove       = $remove;
    }

    public function types(): array
    {
        return ['money', Money::class];
    }

    public function supports(string $type): bool
    {
        return in_array($type, $this->types());
    }

    public function cast(array &$attributes, string $attribute, string $type): void
    {
        if (!isset($attributes[$this->amtAttribute], $attributes[$this->curAttribute])) {
            return;
        }

        $attributes[$attribute] = new Money((float)$attributes[$this->amtAttribute], Currency::memberByKey($attributes[$this->curAttribute]));

        if ($this->remove) {
            unset($attributes[$this->amtAttribute], $attributes[$this->curAttribute]);
        }
    }
}
