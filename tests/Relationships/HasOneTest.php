<?php declare(strict_types=1);

namespace Somnambulist\ReadModels\Tests\Relationships;

use Doctrine\DBAL\Query\QueryBuilder;
use PHPUnit\Framework\TestCase;
use Somnambulist\ReadModels\Model;
use Somnambulist\ReadModels\ModelBuilder;
use Somnambulist\ReadModels\Relationships\AbstractRelationship;
use Somnambulist\ReadModels\Relationships\HasOne;
use Somnambulist\ReadModels\Tests\Stubs\Models\User;
use Somnambulist\ReadModels\Tests\Stubs\Models\UserAlt;
use Somnambulist\ReadModels\Tests\Stubs\Models\UserProfile;

/**
 * Class HasOneTest
 *
 * @package    Somnambulist\ReadModels\Tests
 * @subpackage Somnambulist\ReadModels\Tests\Relationships\HasOneTest
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
}
