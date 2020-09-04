<?php

declare(strict_types=1);

namespace Somnambulist\ReadModels\Tests\Stubs\Models;

use Somnambulist\ReadModels\Model;

/**
 * Class UserProfile
 *
 * @package    Somnambulist\ReadModels\Tests\Stubs\Models
 * @subpackage Somnambulist\ReadModels\Tests\Stubs\Models\UserProfile
 *
 * @property-read User $user
 */
class UserProfile extends Model
{

    protected string $table = 'user_profiles';

    protected ?string $externalPrimaryKey = 'user_uuid';

    protected ?string $foreignKey = 'user_profile_id';

    protected function user()
    {
        return $this->belongsTo(User::class, 'user_uuid', 'uuid');
    }
}
