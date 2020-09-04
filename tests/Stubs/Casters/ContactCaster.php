<?php declare(strict_types=1);

namespace Somnambulist\ReadModels\Tests\Stubs\Casters;

use Somnambulist\Domain\Entities\Types\Identity\EmailAddress;
use Somnambulist\Domain\Entities\Types\PhoneNumber;
use Somnambulist\ReadModels\Contracts\AttributeCaster;
use Somnambulist\ReadModels\Tests\Stubs\Models\Contact;

/**
 * Class ContactCaster
 *
 * @package    Somnambulist\ReadModels\Tests\Stubs\Casters
 * @subpackage Somnambulist\ReadModels\Tests\Stubs\Casters\ContactCaster
 */
class ContactCaster implements AttributeCaster
{
    public function types(): array
    {
        return ['contact', Contact::class];
    }

    public function supports(string $type): bool
    {
        return in_array($type, $this->types());
    }

    public function cast(array &$attributes, string $attribute, string $type): void
    {
        $attributes['contact'] = new Contact(
            $attributes['name'],
            $attributes['contact_phone'] ? new PhoneNumber($attributes['contact_phone']) : null,
            $attributes['contact_email'] ? new EmailAddress($attributes['contact_email']) : null,
        );

        unset($attributes['name'], $attributes['contact_phone'], $attributes['contact_email']);
    }
}
