<?php declare(strict_types=1);

namespace Somnambulist\Components\ReadModels\EventSubscribers;

use Somnambulist\Components\ReadModels\Manager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Messenger\Event\WorkerMessageFailedEvent;
use Symfony\Component\Messenger\Event\WorkerMessageHandledEvent;

/**
 * Clears the read-model identity map when being used in Messenger to avoid stale
 * data after a command has been executed.
 *
 * Based on DoctrineBridge::DoctrineClearEntityManagerWorkerSubscriber
 */
class IdentityMapClearerMessengerSubscriber implements EventSubscriberInterface
{
    public function __construct(private Manager $manager)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            WorkerMessageHandledEvent::class => 'onWorkerMessageHandled',
            WorkerMessageFailedEvent::class  => 'onWorkerMessageFailed',
        ];
    }

    public function onWorkerMessageHandled(): void
    {
        $this->clearIdentityMap();
    }

    public function onWorkerMessageFailed(): void
    {
        $this->clearIdentityMap();
    }

    private function clearIdentityMap(): void
    {
        $this->manager->map()->clear();
    }
}
