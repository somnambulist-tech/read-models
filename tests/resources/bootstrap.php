<?php declare(strict_types=1);

use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Tools\DsnParser;
use Somnambulist\Components\Doctrine\TypeBootstrapper;
use Somnambulist\Components\ReadModels\Manager;
use Somnambulist\Components\ReadModels\Tests\Stubs\Casters\AddressCaster;
use Somnambulist\Components\ReadModels\Tests\Stubs\Casters\ContactCaster;
use Somnambulist\Components\ReadModels\Tests\Stubs\DataGenerator;
use Somnambulist\Components\ReadModels\TypeCasters\DoctrineTypeCaster;
use Symfony\Component\Dotenv\Dotenv;

(new Dotenv)->loadEnv(dirname(__DIR__, 2) . '/.env');

global $connection, $faker;
$connection = DriverManager::getConnection((new DsnParser())->parse($_ENV['TEST_CONNECTION']));

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
