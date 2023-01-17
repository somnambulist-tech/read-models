<?php declare(strict_types=1);

namespace Somnambulist\Components\ReadModels\Tests\Relationships;

use Doctrine\DBAL\Query\QueryBuilder;
use PHPUnit\Framework\TestCase;
use Somnambulist\Components\Collection\Contracts\Collection;
use Somnambulist\Components\ReadModels\Model;
use Somnambulist\Components\ReadModels\ModelBuilder;
use Somnambulist\Components\ReadModels\Relationships\BelongsToMany;
use Somnambulist\Components\ReadModels\Tests\Stubs\Models\User;

/**
 * @group relationships
 * @group relationships-belongs-to-many
 */
class BelongsToManyTest extends TestCase
{

    public function testBelongsToMany()
    {
        $user = new User();
        $rel = $user->getRelationship('roles');

        $this->assertInstanceOf(BelongsToMany::class, $rel);
    }

    public function testObjectCalls()
    {
        $user = new User();
        $rel = $user->getRelationship('roles');

        $this->assertInstanceOf(ModelBuilder::class, $rel->getQuery());
        $this->assertInstanceOf(Model::class, $rel->getRelated());
        $this->assertInstanceOf(Model::class, $rel->getParent());
    }

    public function testPassThroughMethods()
    {
        $user = new User();
        $rel = $user->getRelationship('roles');

        $this->assertInstanceOf(Model::class, $rel->getModel());
        $this->assertInstanceOf(QueryBuilder::class, $rel->getQueryBuilder());
    }

    public function testQueryingRelationship()
    {
        $user = User::query()->fetchFirstOrFail();

        $rel  = $user->relatedTo()->fetch();
        $rel2 = $user->relatedTo;

        $this->assertInstanceOf(Collection::class, $rel);
        $this->assertEquals($rel2->count(), $rel->count());
        $this->assertSame($rel->first(), $rel2->first());
    }
}
