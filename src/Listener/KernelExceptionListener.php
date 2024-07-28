<?php

namespace App\Listener;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

#[AsEventListener]
class KernelExceptionListener
{
    public function __invoke(ExceptionEvent $event): void
    {
        if ($event->getThrowable() instanceof NotFoundHttpException) {
            $event->setResponse(new RedirectResponse('https://google.de/'));
        }
    }
}