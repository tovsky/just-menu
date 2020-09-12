<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Serializer\Encoder\JsonEncoder;

final class NotFoundHttpExceptionEventSubscriber implements EventSubscriberInterface
{
    private const KERNEL_EXCEPTION = 'onKernelException';
    private const CONTENT_TYPE = 'Content-Type';
    private const NOT_FOUND_ERROR_MESSAGE = 'Неправильный URL-адрес';

    public static function getSubscribedEvents(): array
    {
        return [KernelEvents::EXCEPTION => self::KERNEL_EXCEPTION];
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        if (!$event->getThrowable() instanceof NotFoundHttpException) {
            return;
        }
        $event->setResponse($this->jsonResponse($event));
    }

    private function jsonResponse(ExceptionEvent $event): JsonResponse
    {
        return new JsonResponse(
            [
                'message' => self::NOT_FOUND_ERROR_MESSAGE . ': ' . $event->getThrowable()->getMessage()
            ],
            Response::HTTP_NOT_FOUND,
            [self::CONTENT_TYPE => JsonEncoder::FORMAT]
        );
    }
}
