<?php declare(strict_types=1);

namespace Somnambulist\Components\ReadModels\Tests\Support\Behaviours;

trait GetRandomUserId
{

    protected function getRandomUserId()
    {
        // hacky...
        global $connection;

        return $connection->fetchOne('SELECT id FROM users ORDER BY RANDOM() LIMIT 1');
    }
}
