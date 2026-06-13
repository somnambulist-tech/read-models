<?php declare(strict_types=1);

namespace Somnambulist\Components\ReadModels\Tests;

use Pagerfanta\Pagerfanta;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;
use Somnambulist\Components\Collection\Contracts\Collection;
use Somnambulist\Components\Models\Types\DateTime\DateTime;
use Somnambulist\Components\ReadModels\Exceptions\EntityNotFoundException;
use Somnambulist\Components\ReadModels\Exceptions\NoResultsException;
use Somnambulist\Components\ReadModels\Model;
use Somnambulist\Components\ReadModels\ModelBuilder;
use Somnambulist\Components\ReadModels\Tests\Stubs\Models\Role;
use Somnambulist\Components\ReadModels\Tests\Stubs\Models\User;
use Somnambulist\Components\ReadModels\Tests\Stubs\Models\UserAddress;
use Somnambulist\Components\ReadModels\Tests\Stubs\Models\UserContact;
use Somnambulist\Components\ReadModels\Tests\Support\Behaviours\GetRandomUserId;

#[Group("model-builder")]
class ModelBuilderTest extends TestCase
{
    use GetRandomUserId;

    #[Group("find")]
    public function testFind()
    {
        $userId = $this->getRandomUserId();

        $user = User::find($userId);

        $this->assertNotNull($user);

        $this->assertEquals($userId, $user->id);
        $this->assertEquals($userId, $user->id());
    }

    #[Group("find")]
    public function testFindReturnsNull()
    {
        $this->assertNull(User::find(999999999999));
    }

    #[Group("find")]
    public function testFindBy()
    {
        $results = User::query()->findBy(['is_active' => 1], [], 10);

        $this->assertInstanceOf(Collection::class, $results);
        $this->assertCount(10, $results);
    }

    #[Group("find")]
    public function testFindByOrdersResults()
    {
        $results = User::query()->findBy([], ['name' => 'ASC'], 10);

        $this->assertInstanceOf(Collection::class, $results);
        $this->assertCount(10, $results);
    }

    #[Group("find")]
    public function testFindOneBy()
    {
        $result = User::query()->findOneBy(['is_active' => 1]);

        $this->assertInstanceOf(Model::class, $result);
    }

    #[Group("find")]
    public function testFindOrFail()
    {
        $this->expectException(EntityNotFoundException::class);
        $this->expectExceptionMessage('Could not find a record for Somnambulist\Components\ReadModels\Tests\Stubs\Models\User with id and 999999999999');

        User::findOrFail(999999999999);
    }

    #[Group("fetch")]
    public function testFetchOrFail()
    {
        $this->expectException(NoResultsException::class);

        User::query()->where('users.is_active = 5')->fetchFirstOrFail();
    }

    #[Group("fetch")]
    public function testFetchOrNull()
    {
        $this->assertNull(User::query()->where('users.is_active = 5')->fetchFirstOrNull());
    }

    #[Group("paginate")]
    public function testPaginate()
    {
        $pager = User::query()->where('users.is_active = 0')->paginate();

        $this->assertInstanceOf(Pagerfanta::class, $pager);
        $this->assertEquals(1, $pager->getCurrentPage());
        $this->assertNotCount(0, $pager->getCurrentPageResults());
    }

    #[Group("paginate")]
    public function testPaginateFetchPages()
    {
        // expects at least 3 records in the test data
        $pager = User::query()->where('users.is_active = 0')->paginate(3, 1);

        $this->assertInstanceOf(Pagerfanta::class, $pager);
        $this->assertEquals(3, $pager->getCurrentPage());
        $this->assertNotCount(0, $pager->getCurrentPageResults());
    }

    #[Group("paginate")]
    public function testPaginateWithOrderBy()
    {
        // expects at least 3 records in the test data
        $pager = User::query()->where('users.is_active = 0')->orderBy('users.name', 'ASC')->paginate(3, 1);

        $this->assertInstanceOf(Pagerfanta::class, $pager);
        $this->assertEquals(3, $pager->getCurrentPage());
        $this->assertNotCount(0, $pager->getCurrentPageResults());
    }

    #[Group("paginate")]
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

    #[Group("where")]
    public function testWhere()
    {
        $user = User::query()->where('users.is_active = 0')->fetch()->first();

        $this->assertNotNull($user);
        $this->assertFalse($user->is_active);
        $this->assertFalse($user->isActive());
    }

    #[Group("where")]
    public function testWhereWithCallback()
    {
        $user = User::query()->where(function (ModelBuilder $builder) {
            $builder->whereColumn('is_active', '=', 0);
        })->fetch()->first();

        $this->assertNotNull($user);
        $this->assertFalse($user->is_active);
        $this->assertFalse($user->isActive());
    }

    #[Group("where")]
    public function testWhereColumn()
    {
        $user = User::query()->whereColumn('email', 'like', '%gmail.com')->fetch()->first();

        $this->assertNotNull($user);
        $this->assertNotEmpty($user->email);
    }

    #[Group("where")]
    public function testWhereBetween()
    {
        $user = User::query()->whereBetween('created_at', DateTime::parseUtc('-4 weeks'), DateTime::now())->fetch()->first();

        $this->assertNotNull($user);
        $this->assertNotEmpty($user->email);
    }

    #[Group("where")]
    public function testWhereNotBetween()
    {
        $user = User::query()->whereNotBetween('created_at', DateTime::parseUtc('-1 weeks'), DateTime::now())->fetch()->first();

        $this->assertNotNull($user);
        $this->assertNotEmpty($user->email);
    }

    #[Group("where")]
    public function testWhereIn()
    {
        $user = UserAddress::query()->whereIn('type', ['default', 'home', 'work'])->fetch()->first();

        $this->assertNotNull($user);
        $this->assertNotEmpty($user->id);
    }

    #[Group("where")]
    public function testWhereNotIn()
    {
        $user = UserAddress::query()->whereNotIn('type', ['default'])->fetch()->first();

        $this->assertNotNull($user);
        $this->assertNotEmpty($user->id);
    }

    #[Group("where")]
    public function testWhereNull()
    {
        $user = UserContact::query()->whereNull('contact_email')->fetch()->first();

        $this->assertNotNull($user);
        $this->assertNull($user->contact->email);
        $this->assertNotEmpty($user->id);
    }

    #[Group("where")]
    public function testWhereNotNull()
    {
        $user = UserContact::query()->whereNotNull('contact_email')->fetch()->first();

        $this->assertNotNull($user);
        $this->assertNotNull($user->contact->email);
        $this->assertNotEmpty($user->id);
    }

    #[Group("select")]
    public function testSelectCallable()
    {
        $user = User::query()->select(function (ModelBuilder $builder) {
            $builder->query->addSelect('"bob" AS field');
        })->select()->fetch()->first();

        $this->assertNotNull($user);
        $this->assertEquals('bob', $user->field);
    }

    #[Group("select")]
    public function testSelectModelBuilder()
    {
        $groups = Role::query()->select('GROUP_CONCAT(r.name)')->innerJoin('r', 'user_roles', 'g', 'g.role_id = r.id')->where('g.user_id = users.id');

        $results = User::query()->select('*')->select($groups, 'groups')->limit(10)->fetch();

        $results->each(function (Model $user) {
            $this->assertNotNull($user);
            $this->assertNotEmpty($user->groups);
        });
    }

    public function testTap()
    {
        $qb = Role::query()->select('1')->tap(fn (ModelBuilder $q) => $q->whereColumn('id', '>', 1));

        $sql = $qb->query->getSQL();

        $this->assertStringContainsString('SELECT r.1 FROM roles r WHERE r.id > :bind_r_id', $sql);
    }
}
