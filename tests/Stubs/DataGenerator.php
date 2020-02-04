<?php

declare(strict_types=1);

namespace Somnambulist\ReadModels\Tests\Stubs;

use function array_column;
use function array_combine;
use function date;
use Doctrine\DBAL\Connection;
use Faker\Generator;
use function password_hash;

/**
 * Class DataGenerator
 *
 * @package    Somnambulist\ReadModels\Tests\Stubs
 * @subpackage Somnambulist\ReadModels\Tests\Stubs\DataGenerator
 */
class DataGenerator
{

    /**
     * @var Connection
     */
    private $connection;

    /**
     * @var Generator
     */
    private $faker;

    /**
     * Constructor.
     *
     * @param Connection $connection
     * @param Generator    $faker
     */
    public function __construct(Connection $connection, Generator $faker)
    {
        $this->connection = $connection;
        $this->faker      = $faker;
    }

    public function init(): void
    {
        $res = $this->connection->fetchColumn('SELECT id FROM roles LIMIT 1');

        if ($res) {
            return;
        }

        $this->connection->beginTransaction();

        $this->connection->insert('roles', [
            'name' => 'user',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
        $this->connection->insert('roles', [
            'name' => 'admin',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
        $this->connection->insert('roles', [
            'name' => 'switch_user',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
        $this->connection->insert('roles', [
            'name' => 'manager',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        foreach (['user', 'client', 'cart', 'search', 'admin'] as $topic) {
            foreach (['address', 'contact', 'relation', 'import', 'filter'] as $subject) {
                foreach (['create', 'store', 'edit', 'update', 'destroy', 'list'] as $action) {
                    $this->connection->insert('permissions', [
                        'name'       => sprintf('%s.%s.%s', $topic, $subject, $action),
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ]);
                }
            }
        }

        $this->connection->executeQuery('
            INSERT INTO role_permissions (role_id, permission_id)
            SELECT (SELECT id FROM roles WHERE name = \'admin\') AS role_id, id AS permission_id
              FROM permissions
        ');

        $this->connection->executeQuery('
            INSERT INTO role_permissions (role_id, permission_id)
            SELECT (SELECT id FROM roles WHERE name = \'user\') AS role_id, id AS permission_id
              FROM permissions
             WHERE name LIKE \'cart.%\' OR name LIKE \'search.%\'
        ');

        $this->connection->executeQuery('
            INSERT INTO role_permissions (role_id, permission_id)
            SELECT (SELECT id FROM roles WHERE name = \'manager\') AS role_id, id AS permission_id
              FROM permissions
             WHERE name NOT LIKE \'%.destroy\' AND name NOT LIKE \'admin.%\'
        ');

        $this->connection->commit();
    }

    public function build($records = 100): void
    {
        $this->connection->beginTransaction();

        $userRoleId = $this->connection->executeQuery('SELECT id FROM roles WHERE name = ?', ['user'])->fetchColumn();
        $adminRoleId = $this->connection->executeQuery('SELECT id FROM roles WHERE name = ?', ['admin'])->fetchColumn();
        $managerRoleId = $this->connection->executeQuery('SELECT id FROM roles WHERE name = ?', ['manager'])->fetchColumn();
        $switchRoleId = $this->connection->executeQuery('SELECT id FROM roles WHERE name = ?', ['switch_user'])->fetchColumn();

        for ($i=1; $i<=$records; $i++) {
            $this->connection->insert('users', [
                'uuid' => $uuid = $this->faker->uuid,
                'name' => $this->faker->name,
                'is_active' => (int)$this->faker->boolean($chanceOfGettingTrue = 50),
                'created_at' => $this->faker->dateTimeBetween('-12 months', '-1 week')->format('Y-m-d H:i:s'),
                'updated_at' => $this->faker->dateTimeBetween('-2 weeks', 'now')->format('Y-m-d H:i:s'),
                'email' => $this->faker->email,
                'password' => password_hash($this->faker->sha1, PASSWORD_DEFAULT),
            ]);

            $userId = $this->connection->executeQuery('SELECT id FROM users WHERE uuid = ?', [$uuid])->fetchColumn();

            $this->connection->insert('user_roles', ['role_id' => $userRoleId, 'user_id' => $userId]);

            if (rand(1, 5) == 5) {
                $this->connection->insert('user_profiles', [
                    'user_uuid' => $uuid,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                    'profile_name' => $this->faker->name,
                    'profile_text' => $this->faker->realText(2000),
                ]);
            }

            if (rand(1, 5) == 1) {
                $this->connection->insert('user_addresses', [
                    'user_id' => $userId,
                    'type' => 'default',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                    'address_line_1' => $this->faker->streetAddress,
                    'address_line_2' => $this->faker->secondaryAddress,
                    'address_town' => $this->faker->city,
                    'address_county' => $this->faker->state,
                    'address_postcode' => $this->faker->postcode,
                    'country' => $this->faker->countryISOAlpha3,
                ]);

                if (rand(1, 3) == 3) {
                    $this->connection->insert('user_addresses', [
                        'user_id'          => $userId,
                        'type'             => $this->faker->randomElement(['home', 'work', 'delivery']),
                        'created_at'       => date('Y-m-d H:i:s'),
                        'updated_at'       => date('Y-m-d H:i:s'),
                        'address_line_1'   => $this->faker->streetAddress,
                        'address_line_2'   => $this->faker->secondaryAddress,
                        'address_town'     => $this->faker->city,
                        'address_county'   => $this->faker->state,
                        'address_postcode' => $this->faker->postcode,
                        'country'          => $this->faker->countryISOAlpha3,
                    ]);
                }
            }

            if (rand(1, 5) == 3) {
                $this->connection->insert('user_contacts', [
                    'user_id' => $userId,
                    'type' => 'default',
                    'name' => $this->faker->name,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                    'contact_email' => $this->faker->boolean($chanceOfGettingTrue = 50) ? $this->faker->email : null,
                    'contact_phone' => $this->faker->boolean($chanceOfGettingTrue = 50) ? $this->faker->e164PhoneNumber : null,
                ]);
            }

            if (rand(1, 10) == 1) {
                $this->connection->insert('user_roles', [
                    'user_id' => $userId,
                    'role_id' => $managerRoleId,
                ]);
            }

            if (rand(1, 20) == 1) {
                $this->connection->insert('user_roles', [
                    'user_id' => $userId,
                    'role_id' => $adminRoleId,
                ]);
                $this->connection->insert('user_roles', [
                    'user_id' => $userId,
                    'role_id' => $switchRoleId,
                ]);
            }
        }

        $this->connection->commit();

        $ids1 = $this->connection->fetchAll('SELECT id FROM users ORDER BY RANDOM() LIMIT ' . $cnt = rand(1, (int)$records));
        $ids2 = $this->connection->fetchAll('SELECT id FROM users ORDER BY RANDOM() LIMIT ' . $cnt);

        $rels = array_combine(array_column($ids1, 'id'), array_column($ids2, 'id'));

        $this->connection->beginTransaction();

        foreach ($rels as $rel1 => $rel2) {
            if ($rel1 === $rel2) {
                continue;
            }

            $hasRel = $this->connection->fetchColumn('SELECT COUNT(*) AS cnt FROM user_relations WHERE user_source = ? AND user_target = ?', [
                $rel1, $rel2
            ]);

            if ($hasRel > 0) {
                continue;
            }

            $this->connection->insert('user_relations', [
                'user_source' => $rel1,
                'user_target' => $rel2,
            ]);
        }

        $this->connection->commit();
    }
}
