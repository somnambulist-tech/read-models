<?php

declare(strict_types=1);

namespace Somnambulist\ReadModels\Tests\Stubs\Models;

use Somnambulist\ReadModels\Model;

/**
 * Class UserAlt
 *
 * @package    Somnambulist\ReadModels\Tests\Stubs\Models
 * @subpackage Somnambulist\ReadModels\Tests\Stubs\Models\UserAlt
 */
class UserAlt extends Model
{

    protected $table = 'users';

    protected $casts = [
        'uuid' => 'uuid',
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $exports = [
        'uuid' => 'id',
        'name',
        'email',
        'is_active',
        'created_at',
    ];

    protected function address()
    {
        return $this->hasOne(UserAddress::class, 'user_id');
    }

    protected function contact()
    {
        return $this->hasOne(UserContact::class, 'user_id');
    }
}
