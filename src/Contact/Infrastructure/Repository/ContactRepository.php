<?php

declare(strict_types=1);

namespace App\Contact\Infrastructure\Repository;

use App\Contact\Domain\Model\Contact;
use App\Contact\Infrastructure\ORM\ContactPaginator;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;
use RuntimeException;

final class ContactRepository
{
    /** @var ObjectRepository&EntityRepository */
    private $repository;

    /** @var string */
    private $className;

    public function __construct(ManagerRegistry $registry)
    {
        $this->className = Contact::class;
        $repository      = $registry->getRepository(Contact::class);
        if (! $repository instanceof EntityRepository) {
            throw new RuntimeException(EntityRepository::class . ' instance was expected');
        }
        $this->repository = $repository;
    }

    public function find(int $id) : ?Contact
    {
        $contact = $this->repository->find($id);
        if ($contact !== null && ! $contact instanceof Contact) {
            throw new RuntimeException(
                'an ' . Contact::class . ' instance was expected'
            );
        }

        return $contact;
    }

    /**
     * @return object[]|Contact[]
     */
    public function findAll() : array
    {
        return $this->repository->findAll();
    }

    /**
     * @param mixed[]       $criteria
     * @param string[]|null $orderBy
     *
     * @return object[]|Contact[]
     */
    public function findBy(array $criteria, ?array $orderBy = null, ?int $limit = null, ?int $offset = null) : array
    {
        return $this->repository->findBy($criteria, $orderBy, $limit, $offset);
    }

    /**
     * @param mixed[] $criteria
     */
    public function findOneBy(array $criteria) : ?Contact
    {
        $contact = $this->repository->findOneBy($criteria);
        if ($contact !== null && ! $contact instanceof Contact) {
            throw new RuntimeException(
                'an ' . Contact::class . ' instance was expected'
            );
        }

        return $contact;
    }

    public function getClassName() : string
    {
        return $this->className;
    }

    public function createQueryBuilder(string $alias, ?string $indexBy = null) : QueryBuilder
    {
        return $this->repository->createQueryBuilder($alias, $indexBy);
    }

    public function createPaginator(int $page, int $limit) : ContactPaginator
    {
        $query = $this
            ->createQueryBuilder('contacts')
            ->orderBy('contacts.id', 'ASC');

        return new ContactPaginator(new Paginator($query), $page, $limit);
    }
}
