<?php declare(strict_types=1);

namespace Somnambulist\Components\ReadModels\Tests\Stubs\Models;

/**
 * Class Address
 *
 * @package    Somnambulist\Components\ReadModels\Tests\Stubs\Models
 * @subpackage Somnambulist\Components\ReadModels\Tests\Stubs\Models\Address
 */
class Address
{

    private ?string $line1;
    private ?string $line2;
    private ?string $town;
    private ?string $county;
    private ?string $postcode;

    public function __construct(string $line1 = null, string $line2 = null, string $town = null, string $county = null, string $postcode = null)
    {
        $this->line1    = $line1;
        $this->line2    = $line2;
        $this->town     = $town;
        $this->county   = $county;
        $this->postcode = $postcode;
    }

    public function __get($name)
    {
        return $this->{$name} ?? null;
    }
}
