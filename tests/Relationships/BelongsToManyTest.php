<?php declare(strict_types=1);

namespace Somnambulist\ReadModels\Tests\Relationships;

use Doctrine\DBAL\Query\QueryBuilder;
use PHPUnit\Framework\TestCase;
use Somnambulist\ReadModels\Model;
use Somnambulist\ReadModels\ModelBuilder;
use Somnambulist\ReadModels\Relationships\BelongsToMany;
use Somnambulist\ReadModels\Tests\Stubs\Models\User;

/**
 * Class BelongsToManyTest
 *
 * @package    Somnambulist\ReadModels\Tests
 * @subpackage Somnambulist\ReadModels\Tests\Relationships\BelongsToManyTest
 *
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
}
