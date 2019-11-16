<?php

declare(strict_types=1);

namespace App\Contact\Infrastructure\Manager;

use App\Contact\Domain\Model\Contact;
use Doctrine\Common\Persistence\ObjectManager;
use RuntimeException;
use Symfony\Bridge\Doctrine\RegistryInterface;

final class ContactManager
{
    /** @var string */
    private $entityClass;
    /** @var ObjectManager */
    private $objectManager;

    public function __construct(RegistryInterface $registry)
    {
        $this->entityClass = Contact::class;
        $objectManager     = $registry->getManagerForClass($this->entityClass);
        if ($objectManager === null) {
            throw new RuntimeException(
                'No Object Manager found for ' . $this->entityClass . ' Please check your configuration.'
            );
        }
        $this->objectManager = $objectManager;
    }

    public function refresh(Contact $object) : void
    {
        $this->objectManager->refresh($object);
    }

    public function remove(Contact $object) : void
    {
        $this->objectManager->remove($object);
    }

    public function persist(Contact $object) : void
    {
        $this->objectManager->persist($object);
    }

    public function flush() : void
    {
        $this->objectManager->flush();
    }

    public function update(Contact $object) : void
    {
        $this->persist($object);
        $this->flush();
    }
}
