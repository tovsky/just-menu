<?php

declare(strict_types=1);

namespace App\Exception;

use App\Formatter\ConstraintViolationErrorsFormatter;
use RuntimeException;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Throwable;

class ValidationException extends RuntimeException
{
    private const VALIDATION_ERROR = 'Ошибка валидации';

    private ConstraintViolationListInterface $violations;

    public function __construct(ConstraintViolationListInterface $violations, $code = 0, Throwable $previous = null)
    {
        parent::__construct(self::VALIDATION_ERROR, $code, $previous);

        $this->violations = $violations;
    }

    public function getViolations(): ConstraintViolationListInterface
    {
        return $this->violations;
    }

    public function getErrorResponse(): array
    {
        $errorsViolations = ConstraintViolationErrorsFormatter::format($this->getViolations());
        $errors = [];

        foreach ($errorsViolations as $property => $message) {
            $errors['errors'][] = [
                'property' => $property,
                'message' => $message
            ];
        }

        return $errors;
    }
}
