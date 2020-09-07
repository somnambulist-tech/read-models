<?php declare(strict_types=1);

namespace Somnambulist\Components\ReadModels\EventSubscribers;

use Somnambulist\Components\ReadModels\Manager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\KernelEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Class IdentityMapClearerSubscriber
 *
 * Kernel subscriber that clears the identity map onRequest start, exception or
 * terminate ensuring that the identity map is fresh for each request. When
 * running under php-fpm this should not be needed; however if you use a PHP
 * application server, that does not terminate, then the identity map will not
 * be cleared between request (e.g. PHP-PM).
 *
 * @package    Somnambulist\Components\ReadModels\EventSubscribers
 * @subpackage Somnambulist\Components\ReadModels\EventSubscribers\IdentityMapClearerSubscriber
 */
class IdentityMapClearerSubscriber implements EventSubscriberInterface
{

    private Manager $manager;

    public function __construct(Manager $manager)
    {
        $this->manager = $manager;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST   => ['onRequest', 255],
            KernelEvents::TERMINATE => ['onTerminate', -255],
            KernelEvents::EXCEPTION => ['onException', -255],
        ];
    }

    public function onRequest(KernelEvent $event): void
    {
        $this->clearIdentityMap();
    }

    public function onException(KernelEvent $event): void
    {
        $this->clearIdentityMap();
    }

    public function onTerminate(KernelEvent $event): void
    {
        $this->clearIdentityMap();
    }

    private function clearIdentityMap(): void
    {
        $this->manager->map()->clear();
    }
}
