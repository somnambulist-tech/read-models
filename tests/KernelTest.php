<?php declare(strict_types=1);

namespace Somnambulist\Components\ReadModels\Tests;

use Somnambulist\Components\ReadModels\EventSubscribers\IdentityMapClearerSubscriber;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Class KernelTest
 *
 * @package    Somnambulist\Components\ReadModels\Tests
 * @subpackage Somnambulist\Components\ReadModels\Tests\KernelTest
 *
 * @group kernel
 */
class KernelTest extends KernelTestCase
{

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
