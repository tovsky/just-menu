<?php

declare(strict_types=1);

namespace App\Resolver;

use App\Exception\ValidationException;
use Generator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ArgumentValueResolver implements ArgumentValueResolverInterface
{
    private ValidatorInterface $validator;

    private SerializerInterface $serializer;

    public function __construct(
        ValidatorInterface $validator,
        SerializerInterface $serializer
    )
    {
        $this->validator = $validator;
        $this->serializer = $serializer;
    }

    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        try {
            return in_array(ArgumentValueInterface::class, class_implements($argument->getType()), true);
        } catch (\Throwable $exception) {
            return false;
        }
    }

    public function resolve(Request $request, ArgumentMetadata $argument): Generator
    {
        $data = $this->serializer->deserialize($request->getContent(), $argument->getType(), JsonEncoder::FORMAT);

        $violations = $this->validator->validate($data, null, $argument->getType());
        if ($violations->count()) {
            throw new ValidationException($violations);
        }

        yield $data;
    }
}
