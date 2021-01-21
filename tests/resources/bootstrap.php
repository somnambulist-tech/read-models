<?php declare(strict_types=1);

use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Logging\SQLLogger;
use Somnambulist\Components\ReadModels\Manager;
use Somnambulist\Components\ReadModels\Tests\Stubs\Casters\AddressCaster;
use Somnambulist\Components\ReadModels\Tests\Stubs\Casters\ContactCaster;
use Somnambulist\Components\ReadModels\Tests\Stubs\DataGenerator;
use Somnambulist\Components\ReadModels\TypeCasters\DoctrineTypeCaster;
use Somnambulist\Components\Domain\Doctrine\TypeBootstrapper;
use Symfony\Component\Dotenv\Dotenv;

(new Dotenv)->loadEnv(dirname(__DIR__, 2) . '/.env');

$connection = DriverManager::getConnection([
    'url' => $_ENV['TEST_CONNECTION'],
]);
// this doesn't seem to be working at all :confused:
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
        echo PHP_EOL . $sql . PHP_EOL;

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
if (in_array('--debug', $_SERVER['argv'])) {
    $connection->getConfiguration()->setSQLLogger(new EchoSQLLogger());
}

TypeBootstrapper::registerEnumerations();
TypeBootstrapper::registerTypes(TypeBootstrapper::$types);

$faker = Faker\Factory::create('en_US');
$generator = new DataGenerator($connection, $faker);

new Manager(
    [
        'default' => $connection,
    ],
    [
        new DoctrineTypeCaster($connection),
        new AddressCaster(),
        new ContactCaster(),
    ]
);
