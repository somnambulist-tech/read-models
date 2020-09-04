<?php

declare(strict_types=1);

namespace Somnambulist\ReadModels\Tests\Stubs\Models;

use Somnambulist\ReadModels\Manager;
use Somnambulist\ReadModels\Model;

/**
 * Class User
 *
 * @package    Somnambulist\ReadModels\Tests\Stubs\Models
 * @subpackage Somnambulist\ReadModels\Tests\Stubs\Models\User
 */
class User extends Model
{

    protected string $table = 'users';

    protected ?string $externalPrimaryKey = 'uuid';

    protected ?string $foreignKey = 'user_id';

    protected array $casts = [
        'uuid' => 'uuid',
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected array $exports = [
        'attributes' => [
            'uuid' => 'id',
            'name',
            'email',
            'is_active',
            'created_at',
        ],
        'relationships' => [],
    ];

    protected function getRegistrationDayAttribute()
    {
        return $this->created_at->format('l');
    }

    protected function getRegistrationAnniversaryAttribute()
    {
        return $this->created_at->format('dS F Y');
    }

    protected function get1stRegistrationAnniversaryAttribute()
    {
        return $this->created_at->format('dS F Y');
    }

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
        return $this->belongsToMany(Role::class, 'user_roles');
    }

    protected function profile()
    {
        /*
         * For these tests we have to fake the relationship by pre-registering user_uuid
         * to the aliases so that the user profile can be reverse loaded successfully.
         * If you have a more standard naming scheme, this should not be necessary i.e. if
         * the foreign key in user_profiles was user_id instead, this would not be necessary.
         */
        Manager::instance()->map()->registerAlias($this, 'user_uuid');

        return $this->hasOne(UserProfile::class, 'user_uuid', 'uuid');
    }

    protected function fixed_profile()
    {
        /*
         * For these tests we have to fake the relationship by pre-registering user_uuid
         * to the aliases so that the user profile can be reverse loaded successfully.
         * If you have a more standard naming scheme, this should not be necessary i.e. if
         * the foreign key in user_profiles was user_id instead, this would not be necessary.
         */
        Manager::instance()->map()->registerAlias($this, 'user_uuid');

        return $this->hasOne(UserProfile::class, 'user_uuid', 'uuid', false);
    }
}
