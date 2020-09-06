<?php declare(strict_types=1);

namespace Somnambulist\ReadModels\Tests;

use PHPUnit\Framework\TestCase;
use Somnambulist\ReadModels\Tests\Stubs\Models\Role;
use Somnambulist\ReadModels\Tests\Stubs\Models\User;
use Somnambulist\ReadModels\Tests\Stubs\Models\UserAddress;
use Somnambulist\ReadModels\Tests\Support\Behaviours\GetRandomUserId;
use Somnambulist\ReadModels\Tests\Support\Behaviours\GetRandomUserIdWithRelationship;

/**
 * Class ModelEagerLoadingTest
 *
 * @package    Somnambulist\ReadModels\Tests
 * @subpackage Somnambulist\ReadModels\Tests\ModelEagerLoadingTest
 *
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
    public function testWith()
    {
        $userId = $this->getRandomUserIdWithRelationship('user_addresses', 'a', 'a.user_id = u.id');

        $user = User::with('addresses')->find($userId);

        $this->assertNotNull($user);
        $this->assertNotCount(0, $user->addresses);
        $this->assertInstanceOf(UserAddress::class, $user->addresses->first());
    }

    /**
     * @group with
     */
    public function testWithForBelongsTo()
    {
        $user = UserAddress::with('user')->limit(1)->fetch()->first();

        $this->assertNotNull($user);
        $this->assertInstanceOf(User::class, $user->user);
    }

    /**
     * @group with
     */
    public function testWithNestedLoading()
    {
        $userId = $this->getRandomUserIdWithRelationship('user_roles', 'r', 'r.user_id = u.id AND r.role_id = (select id from roles where name = \'admin\')');

        $user = User::with('roles.permissions')->find($userId);

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
