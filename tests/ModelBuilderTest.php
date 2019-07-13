<?php

declare(strict_types=1);

namespace Somnambulist\ReadModels\Tests;

use PHPUnit\Framework\TestCase;
use Somnambulist\Domain\Entities\Types\DateTime\DateTime;
use Somnambulist\ReadModels\Exceptions\EntityNotFoundException;
use Somnambulist\ReadModels\Tests\Stubs\Models\User;
use Somnambulist\ReadModels\Tests\Stubs\Models\UserAddress;
use Somnambulist\ReadModels\Tests\Stubs\Models\UserContact;
use Somnambulist\ReadModels\Tests\Support\Behaviours\GetRandomUserId;

/**
 * Class ModelBuilderTest
 *
 * @package    Somnambulist\ReadModels\Tests
 * @subpackage Somnambulist\ReadModels\Tests\ModelBuilderTest
 * @group model-builder
 */
class ModelBuilderTest extends TestCase
{

    use GetRandomUserId;

    /**
     * @group find
     */
    public function testFind()
    {
        $userId = $this->getRandomUserId();

        $user = User::find($userId);

        $this->assertNotNull($user);

        $this->assertEquals($userId, $user->id);
        $this->assertEquals($userId, $user->id());
    }

    /**
     * @group find
     */
    public function testFindReturnsNull()
    {
        $this->assertNull(User::find(999999999999));
    }

    /**
     * @group find
     */
    public function testFindOrFail()
    {
        $this->expectException(EntityNotFoundException::class);
        $this->expectExceptionMessage('Could not find a record for Somnambulist\ReadModels\Tests\Stubs\Models\User with users and 999999999999');

        User::findOrFail(999999999999);
    }

    /**
     * @group where
     */
    public function testWhere()
    {
        $user = User::where('users.is_active = 0')->fetch()->first();

        $this->assertNotNull($user);
        $this->assertFalse($user->is_active);
        $this->assertFalse($user->isActive());
    }

    /**
     * @group where
     */
    public function testWhereColumn()
    {
        $user = User::whereColumn('email', 'like', '%gmail.com')->fetch()->first();

        $this->assertNotNull($user);
        $this->assertNotEmpty($user->email);
    }

    /**
     * @group where
     */
    public function testWhereBetween()
    {
        $user = User::whereBetween('created_at', DateTime::parseUtc('-4 weeks'), DateTime::now())->fetch()->first();

        $this->assertNotNull($user);
        $this->assertNotEmpty($user->email);
    }

    /**
     * @group where
     */
    public function testWhereNotBetween()
    {
        $user = User::whereNotBetween('created_at', DateTime::parseUtc('-1 weeks'), DateTime::now())->fetch()->first();

        $this->assertNotNull($user);
        $this->assertNotEmpty($user->email);
    }

    /**
     * @group where
     */
    public function testWhereIn()
    {
        $user = UserAddress::whereIn('type', ['default', 'home', 'work'])->fetch()->first();

        $this->assertNotNull($user);
        $this->assertNotEmpty($user->id);
    }

    /**
     * @group where
     */
    public function testWhereNotIn()
    {
        $user = UserAddress::whereNotIn('type', ['default'])->fetch()->first();

        $this->assertNotNull($user);
        $this->assertNotEmpty($user->id);
    }

    /**
     * @group where
     */
    public function testWhereNull()
    {
        $user = UserContact::whereNull('contact_email')->fetch()->first();

        $this->assertNotNull($user);
        $this->assertNull($user->contact->email);
        $this->assertNotEmpty($user->id);
    }

    /**
     * @group where
     */
    public function testWhereNotNull()
    {
        $user = UserContact::whereNotNull('contact_email')->fetch()->first();

        $this->assertNotNull($user);
        $this->assertNotNull($user->contact->email);
        $this->assertNotEmpty($user->id);
    }
}
