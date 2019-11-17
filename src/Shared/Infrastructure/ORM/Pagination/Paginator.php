<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\ORM\Pagination;

use Doctrine\ORM\Tools\Pagination\Paginator as DoctrinePaginator;
use function ceil;

abstract class Paginator extends PaginatorDecorator
{
    /** @var int */
    private $currentPage;
    /** @var int */
    private $limitPerPage;

    public function __construct(
        DoctrinePaginator $paginator,
        int $currentPage = 1,
        int $limitPerPage = 100,
        int $maxLimitPerPage = 100
    ) {
        parent::__construct($paginator);

        if ($limitPerPage > $maxLimitPerPage) {
            $limitPerPage = $maxLimitPerPage;
        }
        $this->currentPage  = $currentPage;
        $this->limitPerPage = $limitPerPage;

        $this
            ->paginator
            ->getQuery()
            ->setFirstResult($this->getPageQueryOffset())
            ->setMaxResults($this->getLimitPerPage());
    }

    public function getTotalPages() : int
    {
        return (int) ceil(
            $this->count() / $this->getLimitPerPage()
        );
    }

    public function getCurrentPage() : int
    {
        return $this->currentPage;
    }

    public function getLimitPerPage() : int
    {
        return $this->limitPerPage;
    }

    protected function getPageQueryOffset() : int
    {
        return (int) ($this->getCurrentPage()-1)*$this->getLimitPerPage();
    }
}
