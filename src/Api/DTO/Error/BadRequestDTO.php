<?php

declare(strict_types=1);

namespace App\Api\DTO\Error;

use JMS\Serializer\Annotation as JMS;

class BadRequestDTO
{
    /**
     * @var ErrorDTO
     * @JMS\Type(ErrorDTO::class)
     */
    private $error;

    public function __construct(ErrorDTO $error)
    {
        $this->error = $error;
    }

    public function getError() : ErrorDTO
    {
        return $this->error;
    }
}
