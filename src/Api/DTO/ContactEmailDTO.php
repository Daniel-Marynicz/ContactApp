<?php

declare(strict_types=1);

namespace App\Api\DTO;

use JMS\Serializer\Annotation as JMS;
use Swagger\Annotations as SWG;

class ContactEmailDTO
{
    /**
     * @var string
     * @JMS\Type("string")
     * @SWG\Property(
     *     type="string",
     *     example="mail@example.org"
     * )
     */
    private $email;

    /**
     * @var string|null
     * @JMS\Type("string")
     * @SWG\Property(
     *     type="string",
     *     example="Home"
     * )
     */
    private $label;

    public function __construct(string $email, ?string $label)
    {
        $this->email = $email;
        $this->label = $label;
    }

    public function getEmail() : string
    {
        return $this->email;
    }

    public function getLabel() : ?string
    {
        return $this->label;
    }
}
