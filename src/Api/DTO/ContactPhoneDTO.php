<?php

declare(strict_types=1);

namespace App\Api\DTO;

use JMS\Serializer\Annotation as JMS;
use Swagger\Annotations as SWG;

class ContactPhoneDTO
{
    /**
     * @var string
     * @JMS\Type("string")
     * @SWG\Property(
     *     type="string",
     *     example="+48 123 123 123"
     * )
     */
    private $phone;

    /**
     * @var string|null
     * @JMS\Type("string")
     * @SWG\Property(
     *     type="string",
     *     example="Mobile"
     * )
     */
    private $label;

    public function __construct(string $phone, ?string $label)
    {
        $this->phone = $phone;
        $this->label = $label;
    }

    public function getPhone() : string
    {
        return $this->phone;
    }

    public function getLabel() : ?string
    {
        return $this->label;
    }
}
