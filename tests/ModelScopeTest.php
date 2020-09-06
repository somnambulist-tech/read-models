<?php declare(strict_types=1);

namespace Somnambulist\ReadModels\Tests;

use PHPUnit\Framework\TestCase;
use Somnambulist\ReadModels\Model;
use Somnambulist\ReadModels\Tests\Stubs\Models\User;

/**
 * Class ModelTest
 *
 * @package    Somnambulist\ReadModels\Tests
 * @subpackage Somnambulist\ReadModels\Tests\ModelTest
 *
 * @group model
 * @group model-scope
 */
class ModelScopeTest extends TestCase
{

    public function testScopeWithArguments()
    {
        $user = User::query()->activeIs(false)->fetchFirstOrFail();

        $this->assertInstanceOf(Model::class, $user);
        $this->assertFalse($user->is_active);
    }

    public function testScopeWithoutArguments()
    {
        $user = User::query()->onlyActive()->fetchFirstOrFail();

        $this->assertInstanceOf(Model::class, $user);
        $this->assertTrue($user->is_active);
    }
}
