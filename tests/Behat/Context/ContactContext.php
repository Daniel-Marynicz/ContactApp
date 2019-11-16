<?php

declare(strict_types=1);

namespace App\Tests\Behat\Context;

use App\Entity\Contact;
use App\Manager\ContactManager;
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
            $contact = new Contact();
            $contact->setName($row['name']);
            $contact->setId((int) $row['id']);
            $this->contactManager->persist($contact);
        }
        $this->contactManager->flush();
    }
}
