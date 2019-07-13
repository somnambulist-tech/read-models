<?php

declare(strict_types=1);

namespace Somnambulist\ReadModels\Tests\Stubs\Models;

use Somnambulist\ReadModels\Model;

/**
 * Class User
 *
 * @package    Somnambulist\ReadModels\Tests\Stubs\Models
 * @subpackage Somnambulist\ReadModels\Tests\Stubs\Models\User
 */
class User extends Model
{

    protected $casts = [
        'uuid' => 'uuid',
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $exports = [
        'attributes' => [
            'uuid' => 'id',
            'name',
            'email',
            'is_active',
            'created_at',
        ],
        'relationships' => [],
    ];

    protected function addresses()
    {
        return $this->hasMany(UserAddress::class, null, null, 'type');
    }

    protected function contacts()
    {
        return $this->hasMany(UserContact::class, null, null, 'type');
    }

    protected function relatedTo()
    {
        return $this->belongsToMany(User::class, 'user_relations', 'user_source', 'user_target');
    }

    protected function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    protected function permissions()
    {
        return $this->hasMany(Permission::class);
    }
}
