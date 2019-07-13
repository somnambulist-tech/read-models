<?php

declare(strict_types=1);

namespace Somnambulist\ReadModels\Tests\Stubs;

use Somnambulist\ReadModels\Model;
use Somnambulist\ReadModels\ModelConfigurator;
use Somnambulist\ReadModels\Tests\Stubs\Models\Role;
use Somnambulist\ReadModels\Tests\Stubs\Models\User;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class MyBundle
 *
 * @package    Somnambulist\ReadModels\Tests\Stubs
 * @subpackage Somnambulist\ReadModels\Tests\Stubs\MyBundle
 */
class MyBundle extends Bundle
{

    public function boot()
    {
        // directly bind -- should ensure that "default" is bound
        Model::bindConnection($this->container->get('doctrine.dbal.default_connection'), User::class);
        //Model::bindConnection($this->container->get('doctrine.dbal.default_connection'));
    }

    private function altSetup()
    {
//        ModelConfigurator::configure([
//            'default' => $this->container->get('doctrine.dbal.default_connection'),
//            User::class => $this->container->get('doctrine.dbal.default_connection'),
//            Role::class => $this->container->get('doctrine.dbal.default_connection'),
//        ], $myCaster, $myFactory);
    }
}
