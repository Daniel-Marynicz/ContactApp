<?php

declare(strict_types=1);

namespace App\Contact\Application\Transformer;

use App\Contact\Application\DTO\ContactDTO;
use App\Contact\Application\DTO\ContactEmailDTO;
use App\Contact\Application\DTO\ContactGetDTO;
use App\Contact\Application\DTO\ContactPhoneDTO;
use App\Contact\Application\DTO\ContactPostDTO;
use App\Contact\Application\DTO\ContactPutDTO;
use App\Contact\Application\DTO\ContactsGetDTO;
use App\Contact\Domain\Model\Contact;
use App\Contact\Domain\Model\ContactEmail;
use App\Contact\Domain\Model\ContactPhoneNumber;
use App\Contact\Infrastructure\ORM\ContactPaginator;

class ContactTransformer
{
    public function modelsToDto(ContactPaginator $paginator) : ContactsGetDTO
    {
        $dtos = [];

        foreach ($paginator as $contact) {
            $dtos[] = $this->modelToDto($contact);
        }

        return new ContactsGetDTO(
            $paginator->getCurrentPage(),
            $paginator->getTotalPages(),
            $paginator->count(),
            $paginator->getLimitPerPage(),
            $dtos
        );
    }

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
            $contact->getUuid(),
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

        $this->removeEmailsFromModel($contactDTO, $contact);
        $this->addEmailsToModel($contactDTO, $contact);

        $this->removePhoneNumbersFromModel($contactDTO, $contact);
        $this->addPhoneNumbersToModel($contactDTO, $contact);

        return $contact;
    }

    private function removeEmailsFromModel(ContactDTO $contactDTO, Contact $contact) : void
    {
        $values = $contact->getEmails();
        foreach ($values as $key => $value) {
            if ($contactDTO->getEmailWithValueAndLabel($value->getValue(), $value->getLabel())) {
                continue;
            }

            $values->remove($key);
        }
    }

    private function addEmailsToModel(ContactDTO $contactDTO, Contact $contact) : void
    {
        foreach ($contactDTO->getEmails() as $value) {
            $contactValue = $contact->getEmailWithValueAndLabel($value->getValue(), $value->getLabel());
            if (! $contactValue instanceof ContactEmail) {
                $contactValue = new ContactEmail($value->getValue());
            }
            $contactValue->setValue($value->getValue());
            $contactValue->setLabel($value->getLabel());
            $contact->addEmail($contactValue);
        }
    }

    private function removePhoneNumbersFromModel(ContactDTO $contactDTO, Contact $contact) : void
    {
        $values = $contact->getPhoneNumbers();
        foreach ($values as $key => $value) {
            if ($contactDTO->getPhoneNumberWithValueAndLabel($value->getValue(), $value->getLabel())) {
                continue;
            }

            $values->remove($key);
        }
    }

    private function addPhoneNumbersToModel(ContactDTO $contactDTO, Contact $contact) : void
    {
        foreach ($contactDTO->getPhoneNumbers() as $value) {
            $contactValue = $contact->getPhoneNumberWithValueAndLabel($value->getValue(), $value->getLabel());
            if (! $contactValue instanceof ContactPhoneNumber) {
                $contactValue = new ContactPhoneNumber($value->getValue());
            }
            $contactValue->setValue($value->getValue());
            $contactValue->setLabel($value->getLabel());
            $contact->addPhoneNumber($contactValue);
        }
    }
}
