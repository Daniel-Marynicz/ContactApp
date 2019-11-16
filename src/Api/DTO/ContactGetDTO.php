<?php

declare(strict_types=1);

namespace App\Api\DTO;

use DateTimeImmutable;
use JMS\Serializer\Annotation as JMS;
use Ramsey\Uuid\UuidInterface;
use Swagger\Annotations as SWG;

class ContactGetDTO extends ContactDTO
{
    /**
     * @var UuidInterface
     * @JMS\Type("string")
     * @SWG\Property(
     *     type="uuid",
     *     example="9d1cd170-773f-4e2d-b115-5d95c3fafaa1"
     * )
     */
    private $uuid;

    /**
     * @var DateTimeImmutable|null
     * @JMS\Type("DateTimeImmutable<'Y-m-d\TH:i:sP'>")
     * @SWG\Property(
     *     format="date-time",
     *     example="1995-09-07T10:40:52Z"
     * )
     */
    private $createdAt;

    /**
     * @var DateTimeImmutable|null
     * @JMS\Type("DateTimeImmutable<'Y-m-d\TH:i:sP'>")
     * @SWG\Property(
     *     format="date-time",
     *     example="1995-09-07T10:40:52Z"
     * )
     */
    private $updatedAt;

    /**
     * @param array<int, ContactEmailDTO> $emails
     * @param array<int, ContactPhoneDTO> $phoneNumbers
     */
    public function __construct(
        UuidInterface $uuid,
        string $name,
        ?DateTimeImmutable $createdAt,
        ?DateTimeImmutable $updatedAt,
        ?string $streetAndNumber,
        ?string $postcode,
        ?string $city,
        ?string $country,
        array $emails = [],
        array $phoneNumbers = []
    ) {
        parent::__construct($name, $streetAndNumber, $postcode, $city, $country, $emails, $phoneNumbers);
        $this->uuid      = $uuid;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    public function getUuid() : UuidInterface
    {
        return $this->uuid;
    }

    public function getCreatedAt() : ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt() : ?DateTimeImmutable
    {
        return $this->updatedAt;
    }
}
