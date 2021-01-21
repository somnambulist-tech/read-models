<?php declare(strict_types=1);

namespace Somnambulist\Components\ReadModels\Tests\Relationships;

use Doctrine\DBAL\Query\QueryBuilder;
use PHPUnit\Framework\TestCase;
use Somnambulist\Components\Collection\MutableCollection;
use Somnambulist\Components\ReadModels\Model;
use Somnambulist\Components\ReadModels\ModelBuilder;
use Somnambulist\Components\ReadModels\Relationships\HasOneToMany;
use Somnambulist\Components\ReadModels\Tests\Stubs\Models\User;
use Somnambulist\Components\ReadModels\Tests\Stubs\Models\UserAddress;
use Somnambulist\Components\ReadModels\Tests\Support\Behaviours\GetRandomUserIdWithRelationship;

/**
 * Class HasManyTest
 *
 * @package    Somnambulist\Components\ReadModels\Tests
 * @subpackage Somnambulist\Components\ReadModels\Tests\Relationships\HasManyTest
 *
 * @package relationships
 * @package relationships-has-many
 */
class HasManyTest extends TestCase
{

    use GetRandomUserIdWithRelationship;

    public function testHasMany()
    {
        $user = new User();
        $rel = $user->getRelationship('contacts');

        $this->assertInstanceOf(HasOneToMany::class, $rel);
    }

    public function testObjectCalls()
    {
        $user = new User();
        $rel = $user->getRelationship('contacts');

        $this->assertInstanceOf(ModelBuilder::class, $rel->getQuery());
        $this->assertInstanceOf(Model::class, $rel->getRelated());
        $this->assertInstanceOf(Model::class, $rel->getParent());
    }

    public function testPassThroughMethods()
    {
        $user = new User();
        $rel = $user->getRelationship('contacts');

        $this->assertInstanceOf(Model::class, $rel->getModel());
        $this->assertInstanceOf(QueryBuilder::class, $rel->getQueryBuilder());
    }

    public function testIndexByOnRelationshipAccess()
    {
        /** @var User $user */
        $user = User::find($this->getRandomUserIdWithRelationship('user_addresses', 'a', 'a.user_id = u.id'));

        $this->assertMatchesRegularExpression('/[a-z]+/', $user->addresses->keys()->implode(','));
    }

    public function testAccessByRelationship()
    {
        /** @var User $user */
        $user = User::find($this->getRandomUserIdWithRelationship('user_addresses', 'a', 'a.user_id = u.id'));

        $address = $user->addresses->first();

        $this->assertInstanceOf(UserAddress::class, $address);
    }

    public function testQueryingRelationship()
    {
        /** @var User $user */
        $user = User::find($this->getRandomUserIdWithRelationship('user_addresses', 'a', 'a.user_id = u.id'));

        $results = $user->addresses()->fetch();

        $this->assertInstanceOf(MutableCollection::class, $results);

        $results->each(fn(UserAddress $addr) => $this->assertEquals($user->id, $addr->user_id));
    }
}
