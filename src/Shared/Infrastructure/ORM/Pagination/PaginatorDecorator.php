<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\ORM\Pagination;

use Doctrine\ORM\Query;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Traversable;

abstract class PaginatorDecorator
{
    /** @var Paginator */
    protected $paginator;

    public function __construct(Paginator $paginator)
    {
        $this->paginator = $paginator;
    }

    /**
     * other decorated methods
     */
    public function getQuery() : Query
    {
        return $this->paginator->getQuery();
    }

    public function getFetchJoinCollection() : bool
    {
        return $this->paginator->getFetchJoinCollection();
    }

    public function getUseOutputWalkers() : ?bool
    {
        return $this->paginator->getUseOutputWalkers();
    }

    /**
     * @return $this
     */
    public function setUseOutputWalkers(?bool $useOutputWalkers) : PaginatorDecorator
    {
        $this->paginator->setUseOutputWalkers($useOutputWalkers);

        return $this;
    }

    public function count() : int
    {
        return $this->paginator->count();
    }

    public function getIterator() : Traversable
    {
        return $this->paginator->getIterator();
    }
}
