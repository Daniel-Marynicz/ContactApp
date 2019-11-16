<?php

declare(strict_types=1);

namespace App\Api\DTO\Contact;

use JMS\Serializer\Annotation as JMS;
use Swagger\Annotations as SWG;
use Symfony\Component\Validator\Constraints as Assert;

class ContactPhoneDTO
{
    /**
     * @var string
     * @JMS\Type("string")
     * @SWG\Property(
     *     type="string",
     *     example="+48 123 123 123"
     * )
     * @Assert\NotBlank()
     */
    private $value;

    /**
     * @var string|null
     * @JMS\Type("string")
     * @SWG\Property(
     *     type="string",
     *     example="Mobile"
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
