<?php declare(strict_types=1);

namespace Somnambulist\Components\ReadModels\Tests;

use BadMethodCallException;
use PHPUnit\Framework\TestCase;
use Somnambulist\Components\Collection\MutableCollection;
use Somnambulist\Components\ReadModels\ModelBuilder;
use Somnambulist\Components\ReadModels\ModelExporter;
use Somnambulist\Components\ReadModels\Relationships\HasOneToMany;
use Somnambulist\Components\ReadModels\Tests\Stubs\Models\User;
use Somnambulist\Components\Models\Types\DateTime\DateTime;
use function date;
use function password_hash;

/**
 * @group model
 */
class ModelTest extends TestCase
{

    public function testBasicObjectMethods()
    {
        $user = new User();

        $this->assertInstanceOf(ModelExporter::class, $user->export());
        $this->assertInstanceOf(ModelBuilder::class, $user->newQuery());
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
            'is_active' => true,
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
            'is_active' => true,
        ]);
        $user->setRelationshipValue('addresses', new MutableCollection());

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
            'is_active' => true,
            'password' => password_hash('password', PASSWORD_DEFAULT),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        $this->assertInstanceOf(HasOneToMany::class, $user->getRelationship('addresses'));
        $this->assertInstanceOf(HasOneToMany::class, $user->getRelationship('contacts'));
    }

    /**
     * @group attributes
     * @group virtual-attributes
     */
    public function testAccessingVirtualAttributes()
    {
        $user = new User([
            'id' => 1,
            'uuid' => '97c0c307-aac2-4486-ab22-45b5fed386c3',
            'email' => 'bob@example.com',
            'name' => 'bob',
            'is_active' => true,
            'password' => password_hash('password', PASSWORD_DEFAULT),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        $this->assertEquals(date('l'), $user->registration_day);
        $this->assertEquals(date('l'), $user->registrationDay());

        $attrs = $user->getAttributes();

        $this->assertArrayHasKey('id', $attrs);
        $this->assertArrayHasKey('registration_day', $attrs);
        $this->assertArrayHasKey('registration_anniversary', $attrs);
        $this->assertArrayHasKey('1st_registration_anniversary', $attrs);
    }
}
