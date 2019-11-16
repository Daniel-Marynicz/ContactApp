<?php

declare(strict_types=1);

namespace App\Api\DTO;

use JMS\Serializer\Annotation as JMS;

class ViolationDTO
{
    /**
     * @var string
     * @JMS\Type("string")
     */
    private $propertyPath;

    /**
     * @var string
     * @JMS\Type("string")
     */
    private $message;

    public function __construct(string $message, string $propertyPath)
    {
        $this->propertyPath = $propertyPath;
        $this->message      = $message;
    }

    public function getPropertyPath() : string
    {
        return $this->propertyPath;
    }

    public function getMessage() : string
    {
        return $this->message;
    }
}
