<?php declare(strict_types=1);

namespace Somnambulist\ReadModels\EventSubscriber;

use Somnambulist\ReadModels\ModelIdentityMap;
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
 * @package    Somnambulist\ReadModels\EventSubscriber
 * @subpackage Somnambulist\ReadModels\EventSubscriber\IdentityMapClearerMessengerSubscriber
 */
class IdentityMapClearerMessengerSubscriber implements EventSubscriberInterface
{

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
        ModelIdentityMap::instance()->clear();
    }
}
