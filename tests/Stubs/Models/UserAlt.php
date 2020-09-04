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

    protected string $table = 'users';

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

    protected function address()
    {
        return $this->hasOne(UserAddress::class);
    }

    protected function contact()
    {
        return $this->hasOne(UserContact::class);
    }
}
