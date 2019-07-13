<?php

declare(strict_types=1);

namespace Somnambulist\ReadModels\Tests\Stubs\Models;

use Somnambulist\Domain\Entities\Types\Identity\EmailAddress;
use Somnambulist\Domain\Entities\Types\PhoneNumber;
use Somnambulist\ReadModels\Model;

/**
 * Class UserContact
 *
 * @package    Somnambulist\ReadModels\Tests\Stubs\Models
 * @subpackage Somnambulist\ReadModels\Tests\Stubs\Models\UserContact
 */
class UserContact extends Model
{

    protected $table = 'user_contacts';

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $embeds = [
        'contact' => [
            Contact::class, [
                'contact_name',
                [PhoneNumber::class, ['?contact_phone_number'], true,],
                [EmailAddress::class, ['?contact_email'], true,],
            ], true
        ]
    ];
}
