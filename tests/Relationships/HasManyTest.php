<?php

declare(strict_types=1);

namespace Somnambulist\ReadModels\Tests;

use Doctrine\DBAL\Query\QueryBuilder;
use PHPUnit\Framework\TestCase;
use Somnambulist\ReadModels\Model;
use Somnambulist\ReadModels\ModelBuilder;
use Somnambulist\ReadModels\Relationships\HasOneToMany;
use Somnambulist\ReadModels\Tests\Stubs\Models\User;
use Somnambulist\ReadModels\Tests\Stubs\Models\UserAddress;
use Somnambulist\ReadModels\Tests\Support\Behaviours\GetRandomUserIdWithRelationship;

/**
 * Class HasManyTest
 *
 * @package    Somnambulist\ReadModels\Tests
 * @subpackage Somnambulist\ReadModels\Tests\HasManyTest
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

        $this->assertTrue($rel->hasMany());
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

        $this->assertRegExp('/[a-z]+/', $user->addresses->keys()->implode(','));
    }

    public function testAccessByRelationship()
    {
        /** @var User $user */
        $user = User::find($this->getRandomUserIdWithRelationship('user_addresses', 'a', 'a.user_id = u.id'));

        $address = $user->addresses->first();

        $this->assertInstanceOf(UserAddress::class, $address);
    }
}
