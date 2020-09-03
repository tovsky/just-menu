<?php

declare(strict_types=1);

namespace App\Formatter;

use Symfony\Component\Validator\ConstraintViolationListInterface;

class ConstraintViolationErrorsFormatter
{
    public static function format(ConstraintViolationListInterface $list): array
    {
        $result = [];

        foreach ($list as $item) {
            $result[$item->getPropertyPath()] = $item->getMessage();
        }

        return $result;
    }
}
