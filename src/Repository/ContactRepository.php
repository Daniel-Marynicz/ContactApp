<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Contact;
use App\ValueObject\PageParameters;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;

class ContactRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        $entityClass = Contact::class;
        parent::__construct($registry, $entityClass);
    }

    /**
     * @param Query|QueryBuilder|null $query
     */
    public function createPaginator(PageParameters $pageParameters, $query = null) : Paginator
    {
        if (! $query) {
            $query = $this
                ->createQueryBuilder('contacts');
            $query->orderBy('contacts.id', 'ASC');
        }
        $query
            ->setFirstResult($pageParameters->getFirstResult())
            ->setMaxResults($pageParameters->getLimitPerPage());

        return new Paginator($query);
    }
}
