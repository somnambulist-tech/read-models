<?php declare(strict_types=1);

namespace Somnambulist\Components\ReadModels\Tests\Stubs\Models;

use Somnambulist\Components\ReadModels\Model;

/**
 * Class UserContact
 *
 * @package    Somnambulist\Components\ReadModels\Tests\Stubs\Models
 * @subpackage Somnambulist\Components\ReadModels\Tests\Stubs\Models\UserContact
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
