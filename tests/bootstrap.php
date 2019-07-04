<?php

declare(strict_types=1);

(new \Symfony\Component\Dotenv\Dotenv)->loadEnv(dirname(__DIR__) . '/.env');

use Doctrine\DBAL\Logging\SQLLogger;

$connection = \Doctrine\DBAL\DriverManager::getConnection([
    'url' => getenv('TEST_CONNECTION'),
]);

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

//        if ($params) {
//            var_dump($params);
//        }
//
//        if (! $types) {
//            return;
//        }
//
//        var_dump($types);
    }

    /**
     * {@inheritdoc}
     */
    public function stopQuery()
    {
    }
}

$connection->getConfiguration()->setSQLLogger(new EchoSQLLogger());

\Somnambulist\ReadModels\Model::bindConnection($connection);

\Somnambulist\Doctrine\Bootstrapper::registerTypes();

Doctrine\DBAL\Types\Type::addType('uuid', \Somnambulist\Doctrine\Types\UuidType::class);
Doctrine\DBAL\Types\Type::addType('geometry', \CrEOF\Spatial\DBAL\Types\GeometryType::class);
