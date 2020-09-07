<?php declare(strict_types=1);

namespace Somnambulist\Components\ReadModels\EventSubscribers;

use Somnambulist\Components\ReadModels\Manager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Messenger\Event\WorkerMessageFailedEvent;
use Symfony\Component\Messenger\Event\WorkerMessageHandledEvent;

/**
 * Class IdentityMapClearerMessengerSubscriber
 *
 * Clears the read-model identity map when being used in Messenger to avoid stale
 * data after a command has been executed.
 *
 * Based on DoctrineBridge::DoctrineClearEntityManagerWorkerSubscriber
 *
 * @package    Somnambulist\Components\ReadModels\EventSubscribers
 * @subpackage Somnambulist\Components\ReadModels\EventSubscribers\IdentityMapClearerMessengerSubscriber
 */
class IdentityMapClearerMessengerSubscriber implements EventSubscriberInterface
{

    private Manager $manager;

    public function __construct(Manager $manager)
    {
        $this->manager = $manager;
    }

    public static function getSubscribedEvents()
    {
        yield WorkerMessageHandledEvent::class => 'onWorkerMessageHandled';
        yield WorkerMessageFailedEvent::class  => 'onWorkerMessageFailed';
    }

    public function onWorkerMessageHandled()
    {
        $this->clearIdentityMap();
    }

    public function onWorkerMessageFailed()
    {
        $this->clearIdentityMap();
    }

    private function clearIdentityMap()
    {
        $this->manager->map()->clear();
    }
}
