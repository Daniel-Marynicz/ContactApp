<?php

declare(strict_types=1);

namespace App\Tests\Behat\Context;

use App\Contact\Domain\Model\Contact;
use App\Contact\Domain\Model\ContactEmail;
use App\Contact\Infrastructure\Manager\ContactManager;
use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;

class ContactContext implements Context
{
    /** @var ContactManager */
    private $contactManager;

    public function __construct(ContactManager $contactManager)
    {
        $this->contactManager = $contactManager;
    }

    /**
     * @Given there are following contacts
     */
    public function thereAreFollowingContacts(TableNode $table) : void
    {
        foreach ($table as $row) {
            $uuid    = $row['uuid'] ?? null;
            $contact = new Contact($uuid);
            $contact->setName($row['name']);
            if (isset($row['email1'])) {
                $contact->addEmail(new ContactEmail($row['email1']));
            }
            if (isset($row['email2'])) {
                $contact->addEmail(new ContactEmail($row['email2']));
            }

            $this->contactManager->persist($contact);
        }
        $this->contactManager->flush();
    }
}
