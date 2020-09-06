<?php declare(strict_types=1);

namespace Somnambulist\ReadModels\Tests\Stubs\Models;

use Somnambulist\ReadModels\Model;

/**
 * Class UserContact
 *
 * @package    Somnambulist\ReadModels\Tests\Stubs\Models
 * @subpackage Somnambulist\ReadModels\Tests\Stubs\Models\UserContact
 */
class UserContact extends Model
{

    protected string $table = 'user_contacts';

    protected array $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'contact'    => Contact::class,
    ];
}
