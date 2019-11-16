<?php

declare(strict_types=1);

namespace App\Shared\Application\Transformer;

use App\Shared\Application\DTO\Error\BadRequestDTO;
use App\Shared\Application\DTO\Error\ErrorDTO;
use App\Shared\Application\DTO\Error\ViolationDTO;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class ConstraintViolationTransformer
{
    public function violationsToDto(ConstraintViolationListInterface $validationErrors) : BadRequestDTO
    {
        $violations = [];
        foreach ($validationErrors as $error) {
            $violations[] = new ViolationDTO($error->getMessage(), $error->getPropertyPath());
        }

        return new BadRequestDTO(
            new ErrorDTO(Response::HTTP_BAD_REQUEST, 'Bad request', $violations)
        );
    }
}
