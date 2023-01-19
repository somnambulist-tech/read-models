<?php declare(strict_types=1);

namespace Somnambulist\Components\ReadModels\Tests;

use PHPUnit\Framework\TestCase;
use Somnambulist\Components\ReadModels\Tests\Stubs\Models\Role;
use Somnambulist\Components\ReadModels\Tests\Stubs\Models\User;
use Somnambulist\Components\ReadModels\Tests\Stubs\Models\UserAddress;
use Somnambulist\Components\ReadModels\Tests\Support\Behaviours\GetRandomUserId;
use Somnambulist\Components\ReadModels\Tests\Support\Behaviours\GetRandomUserIdWithRelationship;

/**
 * @group model
 * @group model-loading
 */
class ModelEagerLoadingTest extends TestCase
{
    use GetRandomUserId;
    use GetRandomUserIdWithRelationship;

    /**
     * @group with
     */
    public function testInclude()
    {
        $userId = $this->getRandomUserIdWithRelationship('user_addresses', 'a', 'a.user_id = u.id');

        $user = User::include('addresses')->find($userId);

        $this->assertNotNull($user);
        $this->assertNotCount(0, $user->addresses);
        $this->assertInstanceOf(UserAddress::class, $user->addresses->first());
    }

    /**
     * @group with
     */
    public function testIncludeForBelongsTo()
    {
        $user = UserAddress::include('user')->limit(1)->fetch()->first();

        $this->assertNotNull($user);
        $this->assertInstanceOf(User::class, $user->user);
    }

    /**
     * @group with
     */
    public function testIncludeNestedLoading()
    {
        $userId = $this->getRandomUserIdWithRelationship('user_roles', 'r', 'r.user_id = u.id AND r.role_id = (select id from roles where name = \'admin\')');

        $user = User::include('roles.permissions')->find($userId);

        $this->assertNotNull($user);
        $this->assertNotCount(0, $user->roles);

        foreach ($user->roles as $role) {
            $this->assertInstanceOf(Role::class, $role);
            if ($role->name !== 'switch_user') {
                $this->assertNotCount(0, $role->permissions);
            }
        }
    }
}
