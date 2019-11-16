<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ContactRepository;
use App\Services\Uuid\UuidProvider;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\Selectable;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Ramsey\Uuid\UuidInterface;

/**
 * @ORM\Entity(repositoryClass=ContactRepository::class)
 */
class Contact
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     *
     * @var int
     */
    private $id;

    /**
     * @ORM\Column(type="uuid", unique=true)
     *
     * @var UuidInterface
     * @Gedmo\Versioned
     */
    private $uuid;

    /**
     * @ORM\Column(unique=true)
     *
     * @var string
     */
    private $name;

    /**
     * Many Contact have Many emails.
     *
     * @ORM\ManyToMany(targetEntity=ContactEmail::class, cascade={"persist"})
     * @ORM\JoinTable(
     *      joinColumns={@ORM\JoinColumn(name="contact_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="email_id", referencedColumnName="id", unique=true)}
     * )
     *
     * @var Collection&Selectable&iterable<ContactEmail>
     */
    private $emails;

    /**
     * Many Contact have Many phone numbers.
     *
     * @ORM\ManyToMany(targetEntity=ContactPhoneNumber::class, cascade={"persist"})
     * @ORM\JoinTable(
     *      joinColumns={@ORM\JoinColumn(name="contact_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="phone_number_id", referencedColumnName="id", unique=true)}
     * )
     *
     * @var Collection&Selectable&iterable<ContactPhoneNumber>
     */
    private $phoneNumbers;

    /**
     * @ORM\Column(nullable=true)
     *
     * @var string|null
     */
    private $country;

    /**
     * @ORM\Column(nullable=true)
     *
     * @var string|null
     */
    private $streetAndNumber;

    /**
     * @ORM\Column(nullable=true)
     *
     * @var string|null
     */
    private $postcode;

    /**
     * @ORM\Column(nullable=true)
     *
     * @var string|null
     */
    private $city;


    /**
     * @ORM\Column(type="datetime_immutable")
     *
     * @var DateTimeImmutable|null
     * @Gedmo\Timestampable(on="create")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime_immutable")
     *
     * @var DateTimeImmutable|null
     * @Gedmo\Timestampable(on="update")
     */
    private $updatedAt;

    public function __construct(?string $uuid = null)
    {
        $this->uuid = $uuid ? UuidProvider::fromString($uuid) : UuidProvider::generate();
        $this->emails       = new ArrayCollection();
        $this->phoneNumbers = new ArrayCollection();
    }

    public function getId() : int
    {
        return $this->id;
    }

    /**
     * @return UuidInterface
     */
    public function getUuid(): UuidInterface
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

    public function getEmailWithValue(string $value) : ?ContactEmail
    {
        $criteria = new Criteria();
        $criteria->where(Criteria::expr()->eq('value', $value));
        $contactEmail = $this->getEmails()->matching($criteria)->first();

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

    public function getPhoneNumberWithValue(string $value) : ?ContactPhoneNumber
    {
        $criteria = new Criteria();
        $criteria->where(Criteria::expr()->eq('value', $value));
        $contactPhoneNumber = $this->getEmails()->matching($criteria)->first();

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
}
