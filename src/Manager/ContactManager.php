<?php

declare(strict_types=1);

namespace App\Manager;

use App\Entity\Contact;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method void refresh(Contact $object)
 * @method void remove(Contact $object)
 * @method void persist(Contact $object)
 */
class ContactManager extends Manager
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Contact::class);
    }
}
