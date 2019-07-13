<?php

declare(strict_types=1);

namespace Somnambulist\ReadModels\Tests\Stubs\Models;

/**
 * Class Address
 *
 * @package    Somnambulist\ReadModels\Tests\Stubs\Models
 * @subpackage Somnambulist\ReadModels\Tests\Stubs\Models\Address
 */
class Address
{

    /**
     * @var string
     */
    private $line1;

    /**
     * @var string
     */
    private $line2;

    /**
     * @var string
     */
    private $town;

    /**
     * @var string
     */
    private $county;

    /**
     * @var string
     */
    private $postcode;

    /**
     * Constructor.
     *
     * @param string|null $line1
     * @param string|null $line2
     * @param string|null $town
     * @param string|null $county
     * @param string|null $postcode
     */
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
