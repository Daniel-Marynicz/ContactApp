<?php

declare(strict_types=1);

namespace App\Api\Transformer;

use App\Api\DTO\ContactDTO;
use App\Api\DTO\ContactEmailDTO;
use App\Api\DTO\ContactGetDTO;
use App\Api\DTO\ContactPhoneDTO;
use App\Api\DTO\ContactPostDTO;
use App\Api\DTO\ContactPutDTO;
use App\Entity\Contact;
use App\Entity\ContactEmail;
use App\Entity\ContactPhoneNumber;

class ContactTransformer
{
    public function modelToDto(Contact $contact) : ContactGetDTO
    {
        $emails = [];

        foreach ($contact->getEmails() as $email) {
            $emails[] = new ContactEmailDTO($email->getValue(), $email->getLabel());
        }
        $phoneNumbers = [];
        foreach ($contact->getPhoneNumbers() as $phoneNumber) {
            $phoneNumbers[] = new ContactPhoneDTO($phoneNumber->getValue(), $phoneNumber->getLabel());
        }

        return new ContactGetDTO(
            $contact->getId(),
            $contact->getName(),
            $contact->getCreatedAt(),
            $contact->getUpdatedAt(),
            $contact->getStreetAndNumber(),
            $contact->getPostcode(),
            $contact->getCity(),
            $contact->getCountry(),
            $emails,
            $phoneNumbers
        );
    }

    public function putDtoToModel(ContactPutDTO $contactDTO, Contact $contact) : Contact
    {
        return $this->dtoToModel($contactDTO, $contact);
    }

    public function postDtoToModel(ContactPostDTO $contactDTO) : Contact
    {
        $contact = new Contact();

        return $this->dtoToModel($contactDTO, $contact);
    }

    private function dtoToModel(ContactDTO $contactDTO, Contact $contact) : Contact
    {
        $contact
            ->setName($contactDTO->getName())
            ->setStreetAndNumber($contactDTO->getStreetAndNumber())
            ->setPostcode($contactDTO->getPostcode())
            ->setCity($contactDTO->getCity())
            ->setCountry($contactDTO->getCountry());

        foreach ($contactDTO->getEmails() as $email) {
            $contactEmail = $contact->getEmailWithValue($email->getEmail());
            if (! $contactEmail instanceof ContactEmail) {
                $contactEmail = new ContactEmail();
            }
            $contactEmail->setValue($email->getEmail());
            $contactEmail->setLabel($email->getLabel());
            $contact->addEmail($contactEmail);
        }

        foreach ($contactDTO->getPhoneNumbers() as $phoneNumber) {
            $contactPhoneNumber = $contact->getPhoneNumberWithValue($phoneNumber->getPhone());
            if (! $contactPhoneNumber instanceof ContactPhoneNumber) {
                $contactPhoneNumber = new ContactPhoneNumber();
            }
            $contactPhoneNumber->setValue($phoneNumber->getPhone());
            $contactPhoneNumber->setLabel($phoneNumber->getLabel());
            $contact->addPhoneNumber($contactPhoneNumber);
        }

        return $contact;
    }
}
