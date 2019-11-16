<?php

declare(strict_types=1);

namespace App\Manager;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use InvalidArgumentException;
use RuntimeException;
use Symfony\Bridge\Doctrine\RegistryInterface;
use function get_class;
use function sprintf;

abstract class Manager
{
    /** @var ObjectManager */
    protected $objectManager;

    /** @var string */
    private $entityClass;

    public function __construct(
        RegistryInterface $registry,
        string $entityClass
    ) {
        $objectManager = $registry->getManagerForClass($entityClass);
        if ($objectManager === null) {
            throw new RuntimeException(
                'No Object Manager found for ' . $entityClass . ' Please check your configuration.'
            );
        }
        $this->objectManager = $objectManager;
        $this->entityClass   = $entityClass;
    }

    public function persist(object $object) : void
    {
        $this->checkObject($object);
        $this->objectManager->persist($object);
    }

    public function remove(object $object) : void
    {
        $this->checkObject($object);
        $this->objectManager->remove($object);
    }

    public function flush() : void
    {
        $this->objectManager->flush();
    }

    public function refresh(object $object) : void
    {
        $this->checkObject($object);
        $this->objectManager->refresh($object);
    }

    public function getRepository() : ObjectRepository
    {
        return $this->objectManager->getRepository($this->getClass());
    }

    public function getClass() : string
    {
        return $this->entityClass;
    }

    protected function checkObject(object $object) : void
    {
        $class = $this->getClass();

        if (! $object instanceof $class) {
            throw new InvalidArgumentException(sprintf(
                'Object must be instance of %s, %s given',
                $class,
                get_class($object)
            ));
        }
    }
}
