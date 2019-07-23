<?php

declare(strict_types=1);

namespace Somnambulist\ReadModels\Tests;

use PHPUnit\Framework\TestCase;
use Somnambulist\ReadModels\Tests\Stubs\Models\User;
use Somnambulist\ReadModels\Tests\Stubs\Models\UserAlt;
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

        $this->assertArrayHasKey('default', $array['addresses']);
        $this->assertArrayHasKey('address_line_1', $array['addresses']['default']);
        $this->assertArrayHasKey('address_line_2', $array['addresses']['default']);
        $this->assertArrayHasKey('town', $array['addresses']['default']);
        $this->assertArrayHasKey('county', $array['addresses']['default']);
        $this->assertArrayHasKey('country', $array['addresses']['default']);
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

    public function testExportNestedRelationships()
    {
        $user = User::find($this->getRandomUserId());

        $array = $user->export()->with('roles.permissions')->toArray();

        $this->assertArrayHasKey('roles', $array);
        $this->assertNotCount(0, $array['roles']);
        $this->assertArrayHasKey('permissions', $array['roles'][0]);
    }

    public function testExportNestedRelationshipsWithAttributes()
    {
        $user = User::find($this->getRandomUserId());

        $array = $user->export()->with('roles.permissions:name')->toArray();

        $this->assertArrayHasKey('roles', $array);
        $this->assertNotCount(0, $array['roles']);
        $this->assertArrayHasKey('permissions', $array['roles'][0]);
        $this->assertArrayHasKey('name', $array['roles'][0]['permissions'][0]);
        $this->assertArrayNotHasKey('id', $array['roles'][0]['permissions'][0]);
        $this->assertArrayNotHasKey('created_at', $array['roles'][0]['permissions'][0]);
        $this->assertArrayNotHasKey('updated_at', $array['roles'][0]['permissions'][0]);
    }

    public function testExportNestedRelationshipsWithAttributesOnEverything()
    {
        $user = User::find($this->getRandomUserId());

        $array = $user->export()->with('roles:name.permissions:name')->toArray();

        $this->assertArrayHasKey('roles', $array);
        $this->assertNotCount(0, $array['roles']);
        $this->assertArrayHasKey('permissions', $array['roles'][0]);
        $this->assertArrayHasKey('name', $array['roles'][0]['permissions'][0]);
        $this->assertArrayNotHasKey('id', $array['roles'][0]['permissions'][0]);
        $this->assertArrayNotHasKey('created_at', $array['roles'][0]['permissions'][0]);
        $this->assertArrayNotHasKey('updated_at', $array['roles'][0]['permissions'][0]);
        $this->assertArrayNotHasKey('id', $array['roles'][0]['permissions'][0]);
        $this->assertArrayNotHasKey('created_at', $array['roles'][0]['permissions'][0]);
        $this->assertArrayNotHasKey('updated_at', $array['roles'][0]['permissions'][0]);
    }

    public function testExportNestedRelationshipsWithoutAttributesDefersToExportSettingsInRelatedModel()
    {
        $user = UserAlt::find($this->getRandomUserIdWithRelationship('user_addresses', 'ua', 'ua.user_id = u.id'));

        $array = $user->export()->with('address')->toArray();

        $this->assertArrayHasKey('address', $array);
        $this->assertArrayHasKey('address_line_1', $array['address']);
        $this->assertArrayHasKey('address_line_2', $array['address']);
        $this->assertArrayHasKey('town', $array['address']);
        $this->assertArrayHasKey('county', $array['address']);
        $this->assertArrayHasKey('postcode', $array['address']);
        $this->assertArrayHasKey('country', $array['address']);
        $this->assertArrayNotHasKey('type', $array['address']);
        $this->assertArrayNotHasKey('id', $array['address']);
        $this->assertArrayNotHasKey('created_at', $array['address']);
        $this->assertArrayNotHasKey('updated_at', $array['address']);
    }

    public function testExportToJson()
    {
        $user = User::find($this->getRandomUserId());

        $json = $user->toJson();
        $compare = json_encode($user->toArray());

        $this->assertEquals($compare, $json);
    }
}
