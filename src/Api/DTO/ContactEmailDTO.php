<?php

declare(strict_types=1);

namespace App\Api\DTO;

use JMS\Serializer\Annotation as JMS;
use Swagger\Annotations as SWG;
use Symfony\Component\Validator\Constraints as Assert;

class ContactEmailDTO
{
    /**
     * @var string
     * @JMS\Type("string")
     * @SWG\Property(
     *     type="string",
     *     example="mail@example.org"
     * )
     * @Assert\Email()
     * @Assert\NotBlank()
     */
    private $value;

    /**
     * @var string|null
     * @JMS\Type("string")
     * @SWG\Property(
     *     type="string",
     *     example="Home"
     * )
     */
    private $label;

    public function __construct(string $value, ?string $label)
    {
        $this->value = $value;
        $this->label = $label;
    }

    public function getValue() : string
    {
        return $this->value;
    }

    public function getLabel() : ?string
    {
        return $this->label;
    }
}
