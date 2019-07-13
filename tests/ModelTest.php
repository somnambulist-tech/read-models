<?php

declare(strict_types=1);

namespace Somnambulist\ReadModels\Tests;

use BadMethodCallException;
use Doctrine\DBAL\Query\Expression\ExpressionBuilder;
use Doctrine\DBAL\Query\QueryBuilder;
use function password_hash;
use PHPUnit\Framework\TestCase;
use Somnambulist\Domain\Entities\Types\DateTime\DateTime;
use Somnambulist\ReadModels\ModelBuilder;
use Somnambulist\ReadModels\ModelExporter;
use Somnambulist\ReadModels\ModelIdentityMap;
use Somnambulist\ReadModels\Relationships\HasOneToMany;
use Somnambulist\ReadModels\Tests\Stubs\Models\User;

/**
 * Class ModelTest
 *
 * @package    Somnambulist\ReadModels\Tests
 * @subpackage Somnambulist\ReadModels\Tests\ModelTest
 * @group model
 */
class ModelTest extends TestCase
{

    public function testBasicObjectMethods()
    {
        $user = new User();

        $this->assertInstanceOf(ModelIdentityMap::class, $user->getIdentityMap());
        $this->assertInstanceOf(ModelExporter::class, $user->export());
        $this->assertInstanceOf(ModelBuilder::class, $user->newQuery());
    }

    public function testMethodPassThrough()
    {
        $user = new User();

        $this->assertInstanceOf(QueryBuilder::class, $user->getQueryBuilder());
        $this->assertInstanceOf(ExpressionBuilder::class, $user->expression());
    }

    public function testUnsupportedMethodRaisesException()
    {
        $this->expectException(BadMethodCallException::class);

        $user = new User();
        $user->foobar();
    }

    public function testUnsupportedMethodRaisesExceptionOnModelBuilder()
    {
        $this->expectException(BadMethodCallException::class);

        $user = new User();
        $user->newQuery()->foobar();
    }

    /**
     * @group attributes
     */
    public function testAttributeAccessors()
    {
        $user = new User([
            'id' => 1,
            'uuid' => '97c0c307-aac2-4486-ab22-45b5fed386c3',
            'email' => 'bob@example.com',
            'name' => 'bob',
            'password' => password_hash('password', PASSWORD_DEFAULT),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        $this->assertEquals('97c0c307-aac2-4486-ab22-45b5fed386c3', (string)$user->uuid);
        $this->assertEquals('97c0c307-aac2-4486-ab22-45b5fed386c3', (string)$user->uuid());
        $this->assertEquals('bob@example.com', $user->email);
        $this->assertInstanceOf(DateTime::class, $user->createdAt());
        $this->assertInstanceOf(DateTime::class, $user->updated_at);
    }

    /**
     * @group attributes
     */
    public function testAttributeExistence()
    {
        $user = new User([
            'id' => 1,
            'uuid' => '97c0c307-aac2-4486-ab22-45b5fed386c3',
            'email' => 'bob@example.com',
            'name' => 'bob',
        ]);

        $this->assertTrue(isset($user->name));
        $this->assertTrue(isset($user->uuid));
        $this->assertFalse(isset($user->created_at));
        $this->assertTrue(isset($user->addresses));
        $this->assertFalse(isset($user->contact));
    }

    /**
     * @group attributes
     */
    public function testRelationshipAccessors()
    {
        $user = new User([
            'id' => 1,
            'uuid' => '97c0c307-aac2-4486-ab22-45b5fed386c3',
            'email' => 'bob@example.com',
            'name' => 'bob',
            'password' => password_hash('password', PASSWORD_DEFAULT),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        $this->assertInstanceOf(HasOneToMany::class, $user->getRelationship('addresses'));
        $this->assertInstanceOf(HasOneToMany::class, $user->getRelationship('contacts'));
    }
}