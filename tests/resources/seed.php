#!/usr/bin/env php
<?php

declare(strict_types=1);

use Doctrine\DBAL\Connection;
use Somnambulist\ReadModels\Tests\Stubs\DataGenerator;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

include_once __DIR__ . '/../../vendor/autoload.php';
include_once __DIR__ . '/bootstrap.php';

$app = new Application('DB Seeder', '1.0.0');
$app->addCommands([
    new class($connection, $faker) extends Symfony\Component\Console\Command\Command
    {
        private $conn;
        private $faker;

        public function __construct($conn, $faker)
        {
            $this->conn  = $conn;
            $this->faker = $faker;

            parent::__construct();
        }

        protected function configure()
        {
            $this
                ->setName('db:seed')
                ->setDescription('Build some randomly generated seed data for testing with')
                ->addOption('records', 'r', InputOption::VALUE_OPTIONAL, 'Number of records to generate', 100)
            ;
        }

        protected function execute(InputInterface $input, OutputInterface $output)
        {
            $generator = new DataGenerator($this->conn, $this->faker);

            $records = $input->getOption('records');

            try {
                $output->writeln('Initialising base records');
                $generator->init();

                $output->writeln('Creating records');
                $generator->build($records);

                $output->writeln('<info>Done</info>');
            } catch (Exception $e) {
                $output->writeln(sprintf('<error>%s</error>', $e->getMessage()));
            }
        }
    },

    new class($connection) extends Symfony\Component\Console\Command\Command
    {
        /**
         * @var Connection
         */
        private $conn;

        public function __construct($conn)
        {
            $this->conn = $conn;

            parent::__construct();
        }


        protected function configure()
        {
            $this
                ->setName('db:destroy')
                ->setDescription('Removes all the records from the database file')
            ;
        }

        protected function execute(InputInterface $input, OutputInterface $output)
        {
            try {
                $output->writeln('<error>Deleting</error> all records in configured test db');

                $this->conn->exec('delete from roles');
                $this->conn->exec('delete from users');
                $this->conn->exec('delete from permissions');
                $this->conn->exec('delete from role_permissions');
                $this->conn->exec('delete from user_addresses');
                $this->conn->exec('delete from user_contacts');
                $this->conn->exec('delete from user_profiles');
                $this->conn->exec('delete from user_relations');
                $this->conn->exec('delete from user_roles');
                $this->conn->exec('vacuum');

                $output->writeln('<info>Done</info>');
            } catch (Exception $e) {
                $output->writeln(sprintf('<error>%s</error>', $e->getMessage()));
            }
        }
    },

    new class($connection) extends Symfony\Component\Console\Command\Command
    {
        /**
         * @var Connection
         */
        private $conn;

        public function __construct($conn)
        {
            $this->conn = $conn;

            parent::__construct();
        }


        protected function configure()
        {
            $this
                ->setName('db:create')
                ->setDescription('Creates the database / file')
            ;
        }

        protected function execute(InputInterface $input, OutputInterface $output)
        {
            try {
                $output->writeln('Creating tables and indexes for db');

                $this->conn->exec('
                    CREATE TABLE IF NOT EXISTS roles (
                        id integer PRIMARY KEY AUTOINCREMENT NOT NULL,
                        name varchar(100) NOT NULL,
                        created_at datetime NOT NULL,
                        updated_at datetime NOT NULL
                    )
                ');
                $this->conn->exec('
                    CREATE TABLE IF NOT EXISTS permissions (
                        id integer PRIMARY KEY AUTOINCREMENT NOT NULL,
                        name varchar(100) NOT NULL,
                        created_at datetime NOT NULL,
                        updated_at datetime NOT NULL
                    )
                ');
                $this->conn->exec('
                    CREATE TABLE IF NOT EXISTS role_permissions (
                        role_id integer NOT NULL,
                        permission_id integer NOT NULL,
                        FOREIGN KEY (role_id) REFERENCES roles (id) ON DELETE CASCADE,
                        FOREIGN KEY (permission_id) REFERENCES permissions (id) ON DELETE CASCADE,
                        PRIMARY KEY(role_id, permission_id)
                    )
                ');

                $this->conn->exec('
                    CREATE TABLE IF NOT EXISTS users (
                        id integer PRIMARY KEY AUTOINCREMENT NOT NULL,
                        uuid char(36) NOT NULL,
                        name varchar(255) NOT NULL,
                        is_active integer(1) NOT NULL DEFAULT(0),
                        created_at datetime NOT NULL,
                        updated_at datetime NOT NULL,
                        email varchar(100) NOT NULL,
                        password varchar(255) NOT NULL
                    )
                ');
                $this->conn->exec('
                    CREATE TABLE IF NOT EXISTS user_addresses (
                        id integer PRIMARY KEY AUTOINCREMENT NOT NULL,
                        user_id integer NOT NULL,
                        type varchar(100) NOT NULL,
                        created_at datetime NOT NULL,
                        updated_at datetime NOT NULL,
                        address_line_1 varchar(255) DEFAULT(NULL),
                        address_line_2 varchar(255) DEFAULT(NULL),
                        address_town varchar(255) DEFAULT(NULL),
                        address_county varchar(255) DEFAULT(NULL),
                        address_postcode varchar(30),
                        country char(3),
                        FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE
                    )
                ');
                $this->conn->exec('
                    CREATE TABLE IF NOT EXISTS user_contacts (
                        id integer PRIMARY KEY AUTOINCREMENT NOT NULL,
                        user_id integer NOT NULL,
                        name varchar(100) NOT NULL,
                        type varchar(100) NOT NULL,
                        created_at datetime NOT NULL,
                        updated_at datetime NOT NULL,
                        contact_phone varchar(20) DEFAULT(NULL),
                        contact_email varchar(100) DEFAULT(NULL),
                        FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE
                    )
                ');
                $this->conn->exec('
                    CREATE TABLE IF NOT EXISTS user_relations (
                        user_source integer NOT NULL,
                        user_target integer NOT NULL,
                        FOREIGN KEY (user_source) REFERENCES users (id) ON DELETE CASCADE,
                        FOREIGN KEY (user_target) REFERENCES users (id) ON DELETE CASCADE,
                        PRIMARY KEY(user_source, user_target)
                    )
                ');
                $this->conn->exec('
                    CREATE TABLE IF NOT EXISTS user_roles (
                        user_id integer NOT NULL,
                        role_id integer NOT NULL,
                        FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE,
                        FOREIGN KEY (role_id) REFERENCES roles (id) ON DELETE CASCADE,
                        PRIMARY KEY(user_id, role_id)
                    )
                ');
                $this->conn->exec('
                    CREATE TABLE IF NOT EXISTS user_profiles (
                        id integer PRIMARY KEY AUTOINCREMENT NOT NULL,
                        user_uuid varchar(36) NOT NULL,
                        created_at datetime NOT NULL,
                        updated_at datetime NOT NULL,
                        profile_name varchar(100) NOT NULL,
                        profile_text text DEFAULT NULL
                    )
                ');

                $this->conn->exec('CREATE UNIQUE INDEX IF NOT EXISTS idx_roles_name ON roles (name ASC)');
                $this->conn->exec('CREATE UNIQUE INDEX IF NOT EXISTS uniq_permissions_name ON permissions (name ASC)');
                $this->conn->exec('CREATE UNIQUE INDEX IF NOT EXISTS uniq_role_permissions_role_id_permission_id ON role_permissions (role_id ASC, permission_id ASC)');
                $this->conn->exec('CREATE INDEX IF NOT EXISTS idx_user_addresses_type ON user_addresses (type ASC)');
                $this->conn->exec('CREATE INDEX IF NOT EXISTS idx_users_is_active ON users (is_active ASC)');
                $this->conn->exec('CREATE UNIQUE INDEX IF NOT EXISTS uniq_users_email ON users (email ASC)');
                $this->conn->exec('CREATE UNIQUE INDEX IF NOT EXISTS uniq_users_uuid ON users (uuid ASC)');
                $this->conn->exec('CREATE UNIQUE INDEX IF NOT EXISTS uniq_user_addresses_user_id_type ON user_addresses (user_id ASC, type ASC)');
                $this->conn->exec('CREATE UNIQUE INDEX IF NOT EXISTS uniq_user_contacts_user_id_type ON user_contacts (user_id ASC, type ASC)');
                $this->conn->exec('CREATE UNIQUE INDEX IF NOT EXISTS uniq_user_relations_user_relationship ON user_relations (user_source ASC, user_target ASC)');
                $this->conn->exec('CREATE UNIQUE INDEX IF NOT EXISTS uniq_user_roles_user_id_role_id ON user_roles (user_id ASC, role_id ASC)');

                $this->conn->exec('vacuum');

                $output->writeln('<info>Done</info>');
            } catch (Exception $e) {
                $output->writeln(sprintf('<error>%s</error>', $e->getMessage()));
            }
        }
    },

]);
$app->run();
