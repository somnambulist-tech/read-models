<?php

declare(strict_types=1);

namespace Somnambulist\ReadModels\Tests;

use Doctrine\DBAL\Connection;
use Somnambulist\ReadModels\EventSubscriber\IdentityMapClearerSubscriber;
use Somnambulist\ReadModels\Model;
use Somnambulist\ReadModels\Tests\Stubs\Models\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Class KernelTest
 *
 * @package    Somnambulist\ReadModels\Tests
 * @subpackage Somnambulist\ReadModels\Tests\KernelTest
 * @group kernel
 */
class KernelTest extends KernelTestCase
{

    public function testBundleRegistersConnection()
    {
        static::bootKernel();

        $this->assertInstanceOf(Connection::class, Model::connection(User::class));
    }

    public function testBundleAndServices()
    {
        $kernel = static::bootKernel();

        /** @var EventDispatcher $evm */
        $evm = $kernel->getContainer()->get('event_dispatcher');
        foreach ($evm->getListeners(KernelEvents::REQUEST) as $listener) {
            if ($listener[0] instanceof IdentityMapClearerSubscriber) {
                $this->assertTrue(true);
                return;
            }
        }

        $this->fail('Unable to find IdentityMapClearerSubscriber in EventDispatcher');
    }

    public function testBundleAndServices2()
    {
        $kernel = static::bootKernel();

        /** @var EventDispatcher $evm */
        $evm = $kernel->getContainer()->get('event_dispatcher');
        foreach ($evm->getListeners(KernelEvents::EXCEPTION) as $listener) {
            if ($listener[0] instanceof IdentityMapClearerSubscriber) {
                $this->assertTrue(true);
                return;
            }
        }

        $this->fail('Unable to find IdentityMapClearerSubscriber in EventDispatcher');
    }

    public function testBundleAndServices3()
    {
        $kernel = static::bootKernel();

        /** @var EventDispatcher $evm */
        $evm = $kernel->getContainer()->get('event_dispatcher');
        foreach ($evm->getListeners(KernelEvents::TERMINATE) as $listener) {
            if ($listener[0] instanceof IdentityMapClearerSubscriber) {
                $this->assertTrue(true);
                return;
            }
        }

        $this->fail('Unable to find IdentityMapClearerSubscriber in EventDispatcher');
    }
}