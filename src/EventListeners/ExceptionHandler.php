<?php

namespace App\EventListeners;

use App\Entity\HypermidiaResponse;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ExceptionHandler implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::EXCEPTION => 'handle404Exception'
        ];
    }

    public function handle404Exception(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();
        if ($exception instanceof NotFoundHttpException) {
            // $event->setResponse(new JsonResponse(['mensagem' => 'Erro 404']));
            $response = HypermidiaResponse::fromError($exception)->getResponse();
            $response->setStatusCode(Response::HTTP_NOT_FOUND);
            $event->setResponse($response);
        }
    }
}