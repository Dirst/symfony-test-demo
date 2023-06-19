<?php

namespace App\EventListener;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class ExceptionListener
{
    public function __invoke(ExceptionEvent $event): void
    {
        $request = $event->getRequest();
        if ('application/json' !== $request->headers->get('Content-Type')) {
            return;
        }

        $response = new JsonResponse(['message' => $event->getThrowable()->getMessage()], 400);
        $event->setResponse($response);
    }
}