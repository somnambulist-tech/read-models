<?php declare(strict_types=1);

namespace Somnambulist\ReadModels\Tests;

use PHPUnit\Framework\TestCase;
use Somnambulist\ReadModels\Tests\Stubs\Models\User;
use Somnambulist\ReadModels\Tests\Stubs\Models\UserAddress;
use Somnambulist\ReadModels\Tests\Stubs\Models\UserAlt;
use Somnambulist\ReadModels\Tests\Support\Behaviours\GetRandomUserId;
use Somnambulist\ReadModels\Tests\Support\Behaviours\GetRandomUserIdWithRelationship;

/**
 * Class ModelIdentityMapTest
 *
 * @package    Somnambulist\ReadModels\Tests
 * @subpackage Somnambulist\ReadModels\Tests\ModelIdentityMapTest
 *
 * @group model
 * @group model-identity-map
 */
class ModelIdentityMapTest extends TestCase
{

    use GetRandomUserId;
    use GetRandomUserIdWithRelationship;

    /**
     * @group eager-loading
     */
    public function testFindSameUserReturnsSameObject()
    {
        $userId = $this->getRandomUserId();

        $user1 = User::find($userId);

        $this->assertNotNull($user1);

        $user2 = User::find($userId);

        $this->assertSame($user1, $user2);
    }

    /**
     * @group eager-loading
     */
    public function testSameObjectOnOneToOne()
    {
        $userId = $this->getRandomUserIdWithRelationship('user_addresses', 'a', 'a.user_id = u.id');

        $user1 = UserAlt::find($userId);

        $this->assertNotNull($user1);

        $user2 = UserAlt::find($userId);

        $this->assertSame($user1->address, $user2->address);
    }

    /**
     * @group eager-loading
     */
    public function testSameObjectOnBelongsTo()
    {
        $obj1 = UserAddress::query()->limit(1)->fetch()->first();

        $this->assertNotNull($obj1);

        $obj2 = UserAddress::query()->limit(1)->fetch()->first();

        $this->assertSame($obj1->user, $obj2->user);
    }

    /**
     * @group eager-loading
     */
    public function testEagerLoadingReturnsSameObject()
    {
        $userId = $this->getRandomUserIdWithRelationship('user_addresses', 'a', 'a.user_id = u.id');

        $user = User::with('addresses')->find($userId);

        $this->assertNotNull($user);
        $this->assertNotCount(0, $user->addresses);
        $this->assertInstanceOf(UserAddress::class, $user->addresses->first());

        $user2 = User::with('addresses')->find($userId);

        $this->assertSame($user->addresses->first(), $user2->addresses->first());
    }

    /**
     * @group eager-loading
     */
    public function testEagerNestedLoadingReturnsSameObject()
    {
        $userId = $this->getRandomUserIdWithRelationship('user_roles', 'r', 'r.user_id = u.id AND r.role_id = (select id from roles where name = \'admin\')');

        $user = User::with('roles.permissions')->find($userId);

        $this->assertNotNull($user);
        $this->assertNotCount(0, $user->roles);

        $user2 = User::with('roles.permissions')->find($userId);

        $perm1 = $user->roles
            ->find(function ($item) { return $item->name == 'admin'; })
            ->permissions
            ->filter(function ($item) {
                return $item->name === 'cart.address.create';
            })
            ->first()
        ;

        $perm2 = $user2->roles
            ->find(function ($item) { return $item->name == 'admin'; })
            ->permissions
            ->filter(function ($item) {
                return $item->name === 'cart.address.create';
            })
            ->first()
        ;

        $this->assertSame($perm1, $perm2);
    }
}
