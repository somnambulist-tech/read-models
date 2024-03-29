<?php declare(strict_types=1);

namespace Somnambulist\Components\ReadModels\Tests\Stubs\Models;

use Somnambulist\Components\Models\Types\Identity\EmailAddress;
use Somnambulist\Components\Models\Types\PhoneNumber;

class Contact
{

    private string $name;
    private ?PhoneNumber $phone;
    private ?EmailAddress $email;

    public function __construct(string $name, ?PhoneNumber $phone, ?EmailAddress $email)
    {
        $this->name  = $name;
        $this->phone = $phone;
        $this->email = $email;
    }

    public function __get($name)
    {
        return $this->{$name} ?? null;
    }

    public function getName()
    {
        return $this->name;
    }

    public function phone(): ?PhoneNumber
    {
        return $this->phone;
    }

    public function email(): ?EmailAddress
    {
        return $this->email;
    }
}
