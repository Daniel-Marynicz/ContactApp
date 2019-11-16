<?php

declare(strict_types=1);

namespace App\Contact\Application\DTO;

use App\Contact\Domain\Model\Contact;
use App\Shared\Infrastructure\Validator\Constraints\UniqueDTO as UniqueDTOConstraint;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\Selectable;
use JMS\Serializer\Annotation as JMS;
use Swagger\Annotations as SWG;
use Symfony\Component\Validator\Constraints as Assert;

class ContactDTO
{
    /**
     * @var string
     * @JMS\Type("string")
     * @SWG\Property(
     *     type="string",
     *     example="Jon Doe"
     * )
     * @UniqueDTOConstraint(
     *     entityClass=Contact::class,
     *     entityPropertyName="name",
     *     requestKey="uuid"
     * )
     */
    private $name;

    /**
     * @var Collection&Selectable&iterable<ContactEmailDTO>
     * @JMS\Type("ArrayCollection<App\Contact\Application\DTO\ContactEmailDTO>")
     * @Assert\Valid()
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
     * @var Collection&Selectable&iterable<ContactPhoneDTO>
     * @JMS\Type("ArrayCollection<App\Contact\Application\DTO\ContactPhoneDTO>")
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
        $this->emails          = new ArrayCollection($emails);
        $this->phoneNumbers    = new ArrayCollection($phoneNumbers);
    }

    public function getName() : string
    {
        return $this->name;
    }

    /**
     * @return Collection&Selectable&iterable<ContactEmailDTO>
     */
    public function getEmails() : Selectable
    {
        if ($this->emails === null) {
            return new ArrayCollection();
        }

        return $this->emails;
    }

    public function getEmailWithValue(string $value) : ?ContactEmailDTO
    {
        $criteria = new Criteria();
        $criteria->where(Criteria::expr()->eq('value', $value));
        $email = $this->getEmails()->matching($criteria)->first();

        return $email ? $email : null;
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
     * @return Collection&Selectable&iterable<ContactPhoneDTO>
     */
    public function getPhoneNumbers() : Selectable
    {
        if ($this->phoneNumbers === null) {
            return new ArrayCollection();
        }

        return $this->phoneNumbers;
    }

    public function getPhoneNumberWithValue(string $value) : ?ContactPhoneDTO
    {
        $criteria = new Criteria();
        $criteria->where(Criteria::expr()->eq('value', $value));
        $phone = $this->getPhoneNumbers()->matching($criteria)->first();

        return $phone ? $phone : null;
    }

    public function getCountry() : ?string
    {
        return $this->country;
    }
}
