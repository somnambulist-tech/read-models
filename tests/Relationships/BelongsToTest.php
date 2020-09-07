<?php declare(strict_types=1);

namespace Somnambulist\Components\ReadModels\Tests\Relationships;

use Doctrine\DBAL\Query\QueryBuilder;
use PHPUnit\Framework\TestCase;
use Somnambulist\Components\ReadModels\Model;
use Somnambulist\Components\ReadModels\Relationships\BelongsTo;
use Somnambulist\Components\ReadModels\Tests\Stubs\Models\User;
use Somnambulist\Components\ReadModels\Tests\Stubs\Models\UserAddress;

/**
 * Class BelongsToTest
 *
 * @package    Somnambulist\Components\ReadModels\Tests
 * @subpackage Somnambulist\Components\ReadModels\Tests\Relationships\BelongsToTest
 *
 * @group relationships
 * @group relationships-belongs-to
 */
class BelongsToTest extends TestCase
{

    public function testBelongsTo()
    {
        $model = new UserAddress();
        $rel = $model->getRelationship('user');

        $this->assertInstanceOf(BelongsTo::class, $rel);

    }

    public function testPassThroughMethods()
    {
        $user = new UserAddress();
        $rel = $user->getRelationship('user');

        $this->assertInstanceOf(Model::class, $rel->getModel());
        $this->assertInstanceOf(QueryBuilder::class, $rel->getQueryBuilder());
    }

    public function testReturnsEmptyObjectIfSetOnRelationship()
    {
        $ua = new UserAddress();

        $this->assertInstanceOf(User::class, $ua->fixed_user);
        $this->assertNull($ua->fixed_user->uuid);
    }
}
