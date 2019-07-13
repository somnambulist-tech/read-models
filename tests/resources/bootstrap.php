<?php

declare(strict_types=1);

use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Logging\SQLLogger;
use Somnambulist\Domain\Doctrine\Bootstrapper;
use Somnambulist\ReadModels\Model;
use Somnambulist\ReadModels\Tests\Stubs\DataGenerator;
use Symfony\Component\Dotenv\Dotenv;

(new Dotenv)->loadEnv(dirname(dirname(__DIR__)) . '/.env');

$connection = DriverManager::getConnection([
    'url' => getenv('TEST_CONNECTION'),
]);
$connection->exec('PRAGMA foreign_keys=on');

/**
 * A SQL logger that logs to the standard output using echo/var_dump.
 */
class EchoSQLLogger implements SQLLogger
{

    /**
     * {@inheritdoc}
     */
    public function startQuery($sql, ?array $params = null, ?array $types = null)
    {
        echo PHP_EOL . $sql;

        if ($params) {
            var_dump($params);
        }

        if (! $types) {
            return;
        }

        var_dump($types);
    }

    /**
     * {@inheritdoc}
     */
    public function stopQuery()
    {

    }
}

// for debugging executed SQL statements
//$connection->getConfiguration()->setSQLLogger(new EchoSQLLogger());

Bootstrapper::registerEnumerations();
Bootstrapper::registerTypes();
Bootstrapper::registerExtendedTypes();
Model::bindConnection($connection);

$faker = Faker\Factory::create('en_US');
$generator = new DataGenerator($connection, $faker);
