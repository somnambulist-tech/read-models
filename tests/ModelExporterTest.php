<?php

declare(strict_types=1);

namespace Somnambulist\ReadModels\Tests;

use PHPUnit\Framework\TestCase;
use Somnambulist\ReadModels\Tests\Stubs\Models\User;
use Somnambulist\ReadModels\Tests\Support\Behaviours\GetRandomUserId;
use Somnambulist\ReadModels\Tests\Support\Behaviours\GetRandomUserIdWithRelationship;

/**
 * Class ModelExporterTest
 *
 * @package    Somnambulist\ReadModels\Tests
 * @subpackage Somnambulist\ReadModels\Tests\ModelExporterTest
 * @group model-exporter
 */
class ModelExporterTest extends TestCase
{

    use GetRandomUserId;
    use GetRandomUserIdWithRelationship;

    public function testExport()
    {
        $user = User::find($this->getRandomUserId());

        $array = $user->toArray();

        $this->assertArrayHasKey('id', $array);
        $this->assertArrayHasKey('name', $array);
        $this->assertArrayHasKey('is_active', $array);
        $this->assertArrayHasKey('created_at', $array);
        $this->assertArrayHasKey('email', $array);
        $this->assertArrayNotHasKey('uuid', $array);
        $this->assertArrayNotHasKey('password', $array);
        $this->assertArrayNotHasKey('updated_at', $array);
    }

    public function testExportNestedObjects()
    {
        $user = User::find($this->getRandomUserIdWithRelationship('user_addresses', 'a', 'a.user_id = u.id'));

        $array = $user->export()->with('addresses')->toArray();

        $this->assertArrayHasKey('id', $array);
        $this->assertArrayHasKey('name', $array);
        $this->assertArrayHasKey('is_active', $array);
        $this->assertArrayHasKey('created_at', $array);
        $this->assertArrayHasKey('email', $array);
        $this->assertArrayNotHasKey('uuid', $array);
        $this->assertArrayNotHasKey('password', $array);
        $this->assertArrayNotHasKey('updated_at', $array);

        $this->assertArrayHasKey('addresses', $array);
        $this->assertIsArray($array['addresses']);

        $this->assertArrayHasKey('type', $array['addresses'][0]);
        $this->assertArrayHasKey('address', $array['addresses'][0]);
        $this->assertArrayHasKey('country', $array['addresses'][0]);
        $this->assertArrayHasKey('created_at', $array['addresses'][0]);
        $this->assertArrayHasKey('updated_at', $array['addresses'][0]);
    }

    public function testExportCustomAttributes()
    {
        $user = User::find($this->getRandomUserIdWithRelationship('user_addresses', 'a', 'a.user_id = u.id'));

        $array = $user->export()->attributes('id', 'name')->toArray();

        $this->assertArrayHasKey('id', $array);
        $this->assertArrayHasKey('name', $array);
        $this->assertArrayNotHasKey('is_active', $array);
        $this->assertArrayNotHasKey('created_at', $array);
        $this->assertArrayNotHasKey('email', $array);
        $this->assertArrayNotHasKey('uuid', $array);
        $this->assertArrayNotHasKey('password', $array);
        $this->assertArrayNotHasKey('updated_at', $array);
    }

    public function testExportCustomAttributesAsArray()
    {
        $user = User::find($this->getRandomUserIdWithRelationship('user_addresses', 'a', 'a.user_id = u.id'));

        $array = $user->export()->attributes(['id', 'name'])->toArray();

        $this->assertArrayHasKey('id', $array);
        $this->assertArrayHasKey('name', $array);
        $this->assertArrayNotHasKey('is_active', $array);
        $this->assertArrayNotHasKey('created_at', $array);
        $this->assertArrayNotHasKey('email', $array);
        $this->assertArrayNotHasKey('uuid', $array);
        $this->assertArrayNotHasKey('password', $array);
        $this->assertArrayNotHasKey('updated_at', $array);
    }

    public function testExportToJson()
    {
        $user = User::find($this->getRandomUserId());

        $json = $user->toJson();
        $compare = json_encode($user->toArray());

        $this->assertEquals($compare, $json);
    }
}
