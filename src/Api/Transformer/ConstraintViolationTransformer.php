<?php

declare(strict_types=1);

namespace App\Api\Transformer;

use App\Api\DTO\BadRequestDTO;
use App\Api\DTO\ErrorDTO;
use App\Api\DTO\ViolationDTO;
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
