<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Exception\ValidationException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;

class ValidationExceptionEventSubscriber implements EventSubscriberInterface
{
    private const KERNEL_EXCEPTION = 'onKernelException';

    private const CONTENT_TYPE = 'Content-Type';

    private SerializerInterface $serializerService;

    public function __construct(SerializerInterface $serializerService)
    {
        $this->serializerService = $serializerService;
    }

    public static function getSubscribedEvents(): array
    {
        return [KernelEvents::EXCEPTION => self::KERNEL_EXCEPTION];
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        if (!$event->getThrowable() instanceof ValidationException) {
            return;
        }
        $event->setResponse($this->jsonResponse($event));
    }

    private function jsonResponse(ExceptionEvent $event): Response
    {
        return new Response(
            $this->serializerService->serialize($event->getThrowable()->getErrorResponse(), JsonEncoder::FORMAT),
            Response::HTTP_BAD_REQUEST,
            [self::CONTENT_TYPE => JsonEncoder::FORMAT]
        );
    }
}
