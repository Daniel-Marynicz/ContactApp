<?php

declare(strict_types=1);

namespace App\Api\DTO;

use JMS\Serializer\Annotation as JMS;
use Swagger\Annotations as SWG;

class ContactDTO
{
    /**
     * @var string
     * @JMS\Type("string")
     * @SWG\Property(
     *     type="string",
     *     example="Jon Doe"
     * )
     */
    private $name;

    /**
     * @var ContactEmailDTO[]
     * @JMS\Type("array<App\Api\DTO\ContactEmailDTO>")
     */
    private $emails;

    /**
     * @var string|null
     * @JMS\Type("string")
     * @SWG\Property(
     *     type="string",
     *     example="ul. Konwaliowa 45B m. 12"
     * )
     */
    private $streetAndNumber;

    /**
     * @var string|null
     * @JMS\Type("string")
     * @SWG\Property(
     *     type="string",
     *     example="12-123 "
     * )
     */
    private $postcode;

    /**
     * @var string|null
     * @JMS\Type("string")
     * @SWG\Property(
     *     type="string",
     *     example="Kraków"
     * )
     */
    private $city;

    /**
     * @var string|null
     * @JMS\Type("string")
     * @SWG\Property(
     *     type="string",
     *     example="Poland"
     * )
     */
    private $country;


    /**
     * @var ContactPhoneDTO[]
     * @JMS\Type("array<App\Api\DTO\ContactPhoneDTO>")
     */
    private $phoneNumbers;

    /**
     * @param array<ContactEmailDTO> $emails
     * @param array<ContactPhoneDTO> $phoneNumbers
     */
    public function __construct(
        string $name,
        ?string $streetAndNumber,
        ?string $postcode,
        ?string $city,
        ?string $country,
        array $emails = [],
        array $phoneNumbers = []
    ) {
        $this->name            = $name;
        $this->streetAndNumber = $streetAndNumber;
        $this->postcode        = $postcode;
        $this->city            = $city;
        $this->country         = $country;
        $this->emails          = $emails;
        $this->phoneNumbers    = $phoneNumbers;
    }

    public function getName() : string
    {
        return $this->name;
    }

    /**
     * @return ContactEmailDTO[]
     */
    public function getEmails() : array
    {
        return $this->emails;
    }

    public function getStreetAndNumber() : ?string
    {
        return $this->streetAndNumber;
    }

    public function getPostcode() : ?string
    {
        return $this->postcode;
    }

    public function getCity() : ?string
    {
        return $this->city;
    }

    /**
     * @return ContactPhoneDTO[]
     */
    public function getPhoneNumbers() : array
    {
        return $this->phoneNumbers;
    }

    public function getCountry() : ?string
    {
        return $this->country;
    }
}
