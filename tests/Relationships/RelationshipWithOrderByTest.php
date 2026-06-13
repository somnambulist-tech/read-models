<?php declare(strict_types=1);

namespace Somnambulist\Components\ReadModels\Tests\Relationships;

use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;
use Somnambulist\Components\ReadModels\Tests\Stubs\Models\Role;

#[Group("relationships")]
#[Group("relationships-orderby")]
class RelationshipWithOrderByTest extends TestCase
{

    #[Group("relationship-order-by")]
    public function testRelationshipCanBeOrdered()
    {
        $role = Role::include('permissions2')->limit(1)->fetchFirstOrFail();

        $this->assertInstanceOf(Role::class, $role);

        $this->assertLessThan($role->permissions2->first()->id(), $role->permissions2->last()->id());
    }

    #[Group("relationship-order-by")]
    public function testRelationshipCanBeOrderedAndFiltered()
    {
        $role = Role::query()->limit(1)->fetchFirstOrFail();

        $results = $role->permissions2()->whereColumn('name', 'LIKE', 'search.relation.%')->fetch();

        $this->assertLessThan($role->permissions->count(), $results->count());
    }
}
