<?php declare(strict_types=1);

namespace Somnambulist\Components\ReadModels\Tests\Stubs\Casters;

use Somnambulist\Components\AttributeModel\Contracts\AttributeCasterInterface;
use Somnambulist\Components\ReadModels\Tests\Stubs\Models\Contact;
use Somnambulist\Components\Domain\Entities\Types\Identity\EmailAddress;
use Somnambulist\Components\Domain\Entities\Types\PhoneNumber;

class ContactCaster implements AttributeCasterInterface
{
    public function types(): array
    {
        return ['contact', Contact::class];
    }

    public function supports(string $type): bool
    {
        return in_array($type, $this->types());
    }

    public function cast(array &$attributes, $attribute, string $type): void
    {
        $attributes['contact'] = new Contact(
            $attributes['name'],
            $attributes['contact_phone'] ? new PhoneNumber($attributes['contact_phone']) : null,
            $attributes['contact_email'] ? new EmailAddress($attributes['contact_email']) : null,
        );

        unset($attributes['name'], $attributes['contact_phone'], $attributes['contact_email']);
    }
}
