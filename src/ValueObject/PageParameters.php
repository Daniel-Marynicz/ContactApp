<?php

declare(strict_types=1);

namespace App\ValueObject;

use InvalidArgumentException;

class PageParameters
{
    public const DEFAULT_PAGE_NUMBER    = 1;
    public const DEFAULT_LIMIT_PER_PAGE = 100;

    /** @var int */
    private $pageNumber;

    /** @var int */
    private $limitPerPage;

    public function __construct(
        int $pageNumber = self::DEFAULT_PAGE_NUMBER,
        int $limitPerPage = self::DEFAULT_LIMIT_PER_PAGE
    ) {
        if ($pageNumber < 1) {
            throw new InvalidArgumentException('Requested page number cannot be less than 1');
        }
        $this->pageNumber = $pageNumber;

        if ($limitPerPage < 1) {
            throw new InvalidArgumentException('Number of results per page cannot be less than 1');
        }
        $this->limitPerPage = $limitPerPage;
    }

    public function getPageNumber() : int
    {
        return $this->pageNumber;
    }

    public function getLimitPerPage() : int
    {
        return $this->limitPerPage;
    }

    public function getFirstResult() : int
    {
        return (int) ($this->getPageNumber()-1)*$this->getLimitPerPage();
    }
}
