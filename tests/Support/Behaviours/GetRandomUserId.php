<?php declare(strict_types=1);

namespace Somnambulist\Components\ReadModels\Tests\Support\Behaviours;

/**
 * Trait GetRandomUserId
 *
 * @package    Somnambulist\Components\ReadModels\Tests\Support\Behaviours
 * @subpackage Somnambulist\Components\ReadModels\Tests\Support\Behaviours\GetRandomUserId
 */
trait GetRandomUserId
{

    protected function getRandomUserId()
    {
        // hacky...
        global $connection;

        return $connection->fetchColumn('SELECT id FROM users ORDER BY RANDOM() LIMIT 1');
    }
}
