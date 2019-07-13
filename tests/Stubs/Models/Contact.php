<?php

declare(strict_types=1);

namespace Somnambulist\ReadModels\Tests\Stubs\Models;


use Somnambulist\Domain\Entities\Types\Identity\EmailAddress;
use Somnambulist\Domain\Entities\Types\PhoneNumber;

/**
 * Class Contact
 *
 * @package    Somnambulist\ReadModels\Tests\Stubs\Models
 * @subpackage Somnambulist\ReadModels\Tests\Stubs\Models\Contact
 */
class Contact
{

    private $name;

    private $phone;

    private $email;

    /**
     * Constructor.
     *
     * @param $name
     * @param $phone
     * @param $email
     */
    public function __construct($name, ?PhoneNumber $phone, ?EmailAddress $email)
    {
        $this->name  = $name;
        $this->phone = $phone;
        $this->email = $email;
    }

    public function __get($name)
    {
        return $this->{$name} ?? null;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return PhoneNumber|null
     */
    public function phone(): ?PhoneNumber
    {
        return $this->phone;
    }

    /**
     * @return EmailAddress|null
     */
    public function email(): ?EmailAddress
    {
        return $this->email;
    }
}
