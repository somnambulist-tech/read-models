<?php declare(strict_types=1);

namespace Somnambulist\Components\ReadModels\Tests\Relationships;

use Doctrine\DBAL\Query\QueryBuilder;
use PHPUnit\Framework\TestCase;
use Somnambulist\Components\ReadModels\Model;
use Somnambulist\Components\ReadModels\ModelBuilder;
use Somnambulist\Components\ReadModels\Relationships\AbstractRelationship;
use Somnambulist\Components\ReadModels\Relationships\HasOne;
use Somnambulist\Components\ReadModels\Tests\Stubs\Models\User;
use Somnambulist\Components\ReadModels\Tests\Stubs\Models\UserAlt;
use Somnambulist\Components\ReadModels\Tests\Stubs\Models\UserProfile;

/**
 * Class HasOneTest
 *
 * @package    Somnambulist\Components\ReadModels\Tests
 * @subpackage Somnambulist\Components\ReadModels\Tests\Relationships\HasOneTest
 *
 * @group relationships
 * @group relationships-has-one
 */
class HasOneTest extends TestCase
{

    public function testHasOne()
    {
        $user = new UserAlt();
        $rel = $user->getRelationship('address');

        $this->assertInstanceOf(HasOne::class, $rel);
    }

    public function testObjectCalls()
    {
        $user = new UserAlt();
        $rel = $user->getRelationship('address');

        $this->assertInstanceOf(ModelBuilder::class, $rel->getQuery());
        $this->assertInstanceOf(Model::class, $rel->getRelated());
        $this->assertInstanceOf(Model::class, $rel->getParent());
    }

    public function testPassThroughMethods()
    {
        $user = new UserAlt();
        $rel = $user->getRelationship('address');

        $this->assertInstanceOf(Model::class, $rel->getModel());
        $this->assertInstanceOf(QueryBuilder::class, $rel->getQueryBuilder());
    }

    public function testMethodCallOnBuilder()
    {
        $user = new UserAlt();
        $rel = $user->getRelationship('address');

        $this->assertInstanceOf(AbstractRelationship::class, $rel->whereNull('town'));
    }

    public function testReturnsEmptyObjectIfSetOnRelationship()
    {
        $user = new User();

        $this->assertInstanceOf(UserProfile::class, $user->fixed_profile);
        $this->assertNull($user->fixed_profile->user_uuid);
    }

    public function testQueryingRelationship()
    {
        $user = User::query()->innerJoin('users', 'user_profiles', 'p', 'p.user_uuid = users.uuid')->fetchFirstOrFail();
        $res  = $user->profile()->fetch();
        $prof = $res->first();

        $this->assertCount(1, $res);
        $this->assertEquals($user->uuid, $prof->user_uuid);
    }
}
