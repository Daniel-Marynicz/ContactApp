<?php

declare(strict_types=1);

namespace App\Contact\Domain\Model;

use App\Shared\Infrastructure\Uuid\UuidProvider;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\Selectable;
use Ramsey\Uuid\UuidInterface;

class Contact
{
    /** @var int */
    private $id;

    /** @var UuidInterface */
    private $uuid;

    /** @var string */
    private $name;

    /**
     * Many Contact have Many emails.
     *
     * @var Collection&Selectable&iterable<ContactEmail>
     */
    private $emails;

    /**
     * Many Contact have Many phone numbers.
     *
     * @var Collection&Selectable&iterable<ContactPhoneNumber>
     */
    private $phoneNumbers;

    /** @var string|null */
    private $country;

    /** @var string|null */
    private $streetAndNumber;

    /** @var string|null */
    private $postcode;

    /** @var string|null */
    private $city;


    /** @var DateTimeImmutable|null */
    private $createdAt;

    /** @var DateTimeImmutable|null */
    private $updatedAt;

    public function __construct(?string $uuid = null)
    {
        $this->uuid         = $uuid ? UuidProvider::fromString($uuid) : UuidProvider::generate();
        $this->emails       = new ArrayCollection();
        $this->phoneNumbers = new ArrayCollection();
    }

    public function getId() : int
    {
        return $this->id;
    }

    public function getUuid() : UuidInterface
    {
        return $this->uuid;
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function setName(string $name) : Contact
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection&Selectable&iterable<ContactEmail>
     */
    public function getEmails() : Selectable
    {
        return $this->emails;
    }

    public function addEmail(ContactEmail $email) : Contact
    {
        if (! $this->emails->contains($email)) {
            $this->emails[] = $email;
        }

        return $this;
    }

    public function getEmailWithValueAndLabel(string $value, ?string $label) : ?ContactEmail
    {
        $contactEmail = $this->getEmails()->matching($this->createValueAndLabelCriteria($value, $label))->first();

        return $contactEmail ? $contactEmail : null;
    }

    /**
     * @return Collection&Selectable&iterable<ContactPhoneNumber>
     */
    public function getPhoneNumbers() : Selectable
    {
        return $this->phoneNumbers;
    }

    public function addPhoneNumber(ContactPhoneNumber $phoneNumber) : Contact
    {
        if (! $this->phoneNumbers->contains($phoneNumber)) {
            $this->phoneNumbers[] = $phoneNumber;
        }

        return $this;
    }

    public function getPhoneNumberWithValueAndLabel(string $value, ?string $label) : ?ContactPhoneNumber
    {
        $contactPhoneNumber = $this
            ->getPhoneNumbers()->matching($this->createValueAndLabelCriteria($value, $label))->first();

        return $contactPhoneNumber ? $contactPhoneNumber : null;
    }

    public function getCountry() : ?string
    {
        return $this->country;
    }

    public function setCountry(?string $country) : Contact
    {
        $this->country = $country;

        return $this;
    }

    public function getStreetAndNumber() : ?string
    {
        return $this->streetAndNumber;
    }

    public function setStreetAndNumber(?string $streetAndNumber) : Contact
    {
        $this->streetAndNumber = $streetAndNumber;

        return $this;
    }

    public function getPostcode() : ?string
    {
        return $this->postcode;
    }

    public function setPostcode(?string $postcode) : Contact
    {
        $this->postcode = $postcode;

        return $this;
    }

    public function getCity() : ?string
    {
        return $this->city;
    }

    public function setCity(?string $city) : Contact
    {
        $this->city = $city;

        return $this;
    }

    public function getCreatedAt() : ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?DateTimeImmutable $createdAt) : Contact
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt() : ?DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?DateTimeImmutable $updatedAt) : Contact
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    private function createValueAndLabelCriteria(string $value, ?string $label) : Criteria
    {
        return Criteria::create()
            ->where(Criteria::expr()->eq('value', $value))
            ->andWhere(Criteria::expr()->eq('label', $label));
    }
}
