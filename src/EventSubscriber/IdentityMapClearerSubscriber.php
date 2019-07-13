<?php

declare(strict_types=1);

namespace Somnambulist\ReadModels\EventSubscriber;

use Somnambulist\ReadModels\Model;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Event\PostResponseEvent;
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
 * @package    Somnambulist\ReadModels\EventSubscriber
 * @subpackage Somnambulist\ReadModels\EventSubscriber\IdentityMapClearerSubscriber
 */
class IdentityMapClearerSubscriber implements EventSubscriberInterface
{

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST   => ['onRequest', 255],
            KernelEvents::TERMINATE => ['onTerminate', -255],
            KernelEvents::EXCEPTION => ['onException', -255],
        ];
    }

    /**
     * @param GetResponseEvent $event
     */
    public function onRequest(GetResponseEvent $event)
    {
        Model::getIdentityMap()->clear();
    }

    /**
     * @param GetResponseForExceptionEvent $event
     */
    public function onException(GetResponseForExceptionEvent $event)
    {
        Model::getIdentityMap()->clear();
    }

    /**
     * @param PostResponseEvent $event
     */
    public function onTerminate(PostResponseEvent $event)
    {
        Model::getIdentityMap()->clear();
    }
}
