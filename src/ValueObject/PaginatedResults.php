<?php

declare(strict_types=1);

namespace App\ValueObject;

use Doctrine\ORM\Tools\Pagination\Paginator;

class PaginatedResults
{
    /** @var Paginator|mixed[] */
    private $paginator;

    /** @var int */
    private $pageNumber;

    /** @var int */
    private $totalPages;

    /** @var int */
    private $limit;

    /** @var int */
    private $count;

    public function __construct(Paginator $paginator, int $pageNumber, int $totalPages, int $limit, int $count)
    {
        $this->paginator  = $paginator;
        $this->pageNumber = $pageNumber;
        $this->totalPages = $totalPages;
        $this->limit      = $limit;
        $this->count      = $count;
    }

    public function getPageNumber() : int
    {
        return $this->pageNumber;
    }

    public function getTotalPages() : int
    {
        return $this->totalPages;
    }

    public function getLimit() : int
    {
        return $this->limit;
    }

    public function getCount() : int
    {
        return $this->count;
    }

    /**
     * @return Paginator|mixed[]
     */
    public function getPaginator() : Paginator
    {
        return $this->paginator;
    }
}
