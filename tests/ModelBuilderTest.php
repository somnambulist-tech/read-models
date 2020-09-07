<?php declare(strict_types=1);

namespace Somnambulist\Components\ReadModels\Tests;

use Pagerfanta\Pagerfanta;
use PHPUnit\Framework\TestCase;
use Somnambulist\Collection\Contracts\Collection;
use Somnambulist\Components\ReadModels\Exceptions\EntityNotFoundException;
use Somnambulist\Components\ReadModels\Exceptions\NoResultsException;
use Somnambulist\Components\ReadModels\Model;
use Somnambulist\Components\ReadModels\ModelBuilder;
use Somnambulist\Components\ReadModels\Tests\Stubs\Models\Role;
use Somnambulist\Components\ReadModels\Tests\Stubs\Models\User;
use Somnambulist\Components\ReadModels\Tests\Stubs\Models\UserAddress;
use Somnambulist\Components\ReadModels\Tests\Stubs\Models\UserContact;
use Somnambulist\Components\ReadModels\Tests\Support\Behaviours\GetRandomUserId;
use Somnambulist\Domain\Entities\Types\DateTime\DateTime;

/**
 * Class ModelBuilderTest
 *
 * @package    Somnambulist\Components\ReadModels\Tests
 * @subpackage Somnambulist\Components\ReadModels\Tests\ModelBuilderTest
 *
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
    public function testFindBy()
    {
        $results = User::query()->findBy(['is_active' => 1], [], 10);

        $this->assertInstanceOf(Collection::class, $results);
        $this->assertCount(10, $results);
    }

    /**
     * @group find
     */
    public function testFindByOrdersResults()
    {
        $results = User::query()->findBy([], ['name' => 'ASC'], 10);

        $this->assertInstanceOf(Collection::class, $results);
        $this->assertCount(10, $results);
    }

    /**
     * @group find
     */
    public function testFindOneBy()
    {
        $result = User::query()->findOneBy(['is_active' => 1]);

        $this->assertInstanceOf(Model::class, $result);
    }

    /**
     * @group find
     */
    public function testFindOrFail()
    {
        $this->expectException(EntityNotFoundException::class);
        $this->expectExceptionMessage('Could not find a record for Somnambulist\Components\ReadModels\Tests\Stubs\Models\User with id and 999999999999');

        User::findOrFail(999999999999);
    }

    /**
     * @group fetch
     */
    public function testFetchOrFail()
    {
        $this->expectException(NoResultsException::class);

        User::query()->where('users.is_active = 5')->fetchFirstOrFail();
    }

    /**
     * @group fetch
     */
    public function testFetchOrNull()
    {
        $this->assertNull(User::query()->where('users.is_active = 5')->fetchFirstOrNull());
    }

    /**
     * @group paginate
     */
    public function testPaginate()
    {
        $pager = User::query()->where('users.is_active = 0')->paginate();

        $this->assertInstanceOf(Pagerfanta::class, $pager);
        $this->assertEquals(1, $pager->getCurrentPage());
        $this->assertNotCount(0, $pager->getCurrentPageResults());
    }

    /**
     * @group paginate
     */
    public function testPaginateFetchPages()
    {
        // expects at least 3 records in the test data
        $pager = User::query()->where('users.is_active = 0')->paginate(3, 1);

        $this->assertInstanceOf(Pagerfanta::class, $pager);
        $this->assertEquals(3, $pager->getCurrentPage());
        $this->assertNotCount(0, $pager->getCurrentPageResults());
    }

    /**
     * @group paginate
     */
    public function testPaginateWithOrderBy()
    {
        // expects at least 3 records in the test data
        $pager = User::query()->where('users.is_active = 0')->orderBy('users.name', 'ASC')->paginate(3, 1);

        $this->assertInstanceOf(Pagerfanta::class, $pager);
        $this->assertEquals(3, $pager->getCurrentPage());
        $this->assertNotCount(0, $pager->getCurrentPageResults());
    }

    /**
     * @group paginate
     */
    public function testPaginateWithGroupBy()
    {
        $pager = User::query()
            ->leftJoin('users', 'user_roles', 'r', 'r.user_id = users.id')
            ->leftJoin('r', 'role_permissions', 'p', 'p.role_id = r.role_id')
            ->where('p.permission_id IS NOT NULL')
            ->groupBy('users.id')
            ->groupBy('p.permission_id')
            ->paginate(1, 1)
        ;

        $this->assertInstanceOf(Pagerfanta::class, $pager);
        $this->assertEquals(1, $pager->getCurrentPage());
        $this->assertNotCount(0, $pager->getCurrentPageResults());
        $this->assertNotEquals(0, $pager->getNbResults());
    }

    /**
     * @group where
     */
    public function testWhere()
    {
        $user = User::query()->where('users.is_active = 0')->fetch()->first();

        $this->assertNotNull($user);
        $this->assertFalse($user->is_active);
        $this->assertFalse($user->isActive());
    }

    /**
     * @group where
     */
    public function testWhereWithCallback()
    {
        $user = User::query()->where(function (ModelBuilder $builder) {
            $builder->whereColumn('is_active', '=', 0);
        })->fetch()->first();

        $this->assertNotNull($user);
        $this->assertFalse($user->is_active);
        $this->assertFalse($user->isActive());
    }

    /**
     * @group where
     */
    public function testWhereColumn()
    {
        $user = User::query()->whereColumn('email', 'like', '%gmail.com')->fetch()->first();

        $this->assertNotNull($user);
        $this->assertNotEmpty($user->email);
    }

    /**
     * @group where
     */
    public function testWhereBetween()
    {
        $user = User::query()->whereBetween('created_at', DateTime::parseUtc('-4 weeks'), DateTime::now())->fetch()->first();

        $this->assertNotNull($user);
        $this->assertNotEmpty($user->email);
    }

    /**
     * @group where
     */
    public function testWhereNotBetween()
    {
        $user = User::query()->whereNotBetween('created_at', DateTime::parseUtc('-1 weeks'), DateTime::now())->fetch()->first();

        $this->assertNotNull($user);
        $this->assertNotEmpty($user->email);
    }

    /**
     * @group where
     */
    public function testWhereIn()
    {
        $user = UserAddress::query()->whereIn('type', ['default', 'home', 'work'])->fetch()->first();

        $this->assertNotNull($user);
        $this->assertNotEmpty($user->id);
    }

    /**
     * @group where
     */
    public function testWhereNotIn()
    {
        $user = UserAddress::query()->whereNotIn('type', ['default'])->fetch()->first();

        $this->assertNotNull($user);
        $this->assertNotEmpty($user->id);
    }

    /**
     * @group where
     */
    public function testWhereNull()
    {
        $user = UserContact::query()->whereNull('contact_email')->fetch()->first();

        $this->assertNotNull($user);
        $this->assertNull($user->contact->email);
        $this->assertNotEmpty($user->id);
    }

    /**
     * @group where
     */
    public function testWhereNotNull()
    {
        $user = UserContact::query()->whereNotNull('contact_email')->fetch()->first();

        $this->assertNotNull($user);
        $this->assertNotNull($user->contact->email);
        $this->assertNotEmpty($user->id);
    }

    /**
     * @group select
     */
    public function testSelectCallable()
    {
        $user = User::query()->select(function (ModelBuilder $builder) {
            $builder->query->addSelect('"bob" AS field');
        })->select()->fetch()->first();

        $this->assertNotNull($user);
        $this->assertEquals('bob', $user->field);
    }

    /**
     * @group select
     */
    public function testSelectModelBuilder()
    {
        $groups = Role::query()->select('GROUP_CONCAT(r.name)')->innerJoin('r', 'user_roles', 'g', 'g.role_id = r.id')->where('g.user_id = users.id');

        $results = User::query()->select('*')->select($groups, 'groups')->limit(10)->fetch();

        $results->each(function (Model $user) {
            $this->assertNotNull($user);
            $this->assertNotEmpty($user->groups);
        });
    }
}
