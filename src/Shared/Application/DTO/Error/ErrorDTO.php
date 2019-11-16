<?php

declare(strict_types=1);

namespace App\Shared\Application\DTO\Error;

use JMS\Serializer\Annotation as JMS;

class ErrorDTO
{
    /**
     * @var int
     * @JMS\Type("int")
     */
    private $code;

    /**
     * @var string
     * @JMS\Type("string")
     */
    private $message;

    /**
     * @var ViolationDTO[]
     * @JMS\Type("array<App\Shared\Application\DTO\Error\ViolationDTO>")
     */
    private $violations;

    /**
     * @param ViolationDTO[] $violations
     */
    public function __construct(int $code, string $message, array $violations)
    {
        $this->code       = $code;
        $this->message    = $message;
        $this->violations = $violations;
    }

    public function getCode() : int
    {
        return $this->code;
    }

    public function getMessage() : string
    {
        return $this->message;
    }

    /**
     * @return ViolationDTO[]
     */
    public function getViolations() : array
    {
        return $this->violations;
    }
}
