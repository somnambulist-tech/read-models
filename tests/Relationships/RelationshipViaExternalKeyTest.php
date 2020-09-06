<?php declare(strict_types=1);

namespace Somnambulist\ReadModels\Tests\Relationships;

use PHPUnit\Framework\TestCase;
use Somnambulist\ReadModels\Tests\Stubs\Models\User;
use Somnambulist\ReadModels\Tests\Stubs\Models\UserProfile;

/**
 * Class RelationshipViaExternalKeyTest
 *
 * @package    Somnambulist\ReadModels\Tests\Relationships
 * @subpackage Somnambulist\ReadModels\Tests\Relationships\RelationshipViaExternalKeyTest
 *
 * @group relationships
 */
class RelationshipViaExternalKeyTest extends TestCase
{

    /**
     * @group external-key
     */
    public function testExternalPrimaryKey()
    {
        $profile = UserProfile::with('user')->limit(1)->fetch()->first();

        $this->assertInstanceOf(User::class, $profile->user);
        $this->assertEquals($profile->user_uuid, $profile->user->uuid);
    }

    /**
     * @group external-key
     */
    public function testExternalPrimaryKeyOnSource()
    {
        $profile = UserProfile::query()->limit(1)->fetchFirstOrNull();

        $user = User::with('profile')->whereColumn('uuid', '=', $profile->user_uuid)->limit(1)->fetchFirstOrNull();

        $this->assertInstanceOf(UserProfile::class, $user->profile);
        $this->assertEquals($user->uuid, $user->profile->user_uuid);
    }

    /**
     * @group external-key
     */
    public function testExternalPrimaryKeyUsesIdentityMap()
    {
        $profile = UserProfile::with('user')->limit(1)->fetch()->first();

        $profile2 = UserProfile::with('user')->find($profile->id);

        $this->assertSame($profile->user, $profile2->user);
    }
}
