<?php

declare(strict_types=1);

namespace App\ValueObject;

use Doctrine\ORM\Tools\Pagination\Paginator;
use function ceil;

class PaginatedResultsFactory
{
    public static function createPaginatedResults(
        Paginator $paginator,
        PageParameters $pageParameters
    ) : PaginatedResults {
        $totalPages = (int) ceil(
            $paginator->count() / $paginator->getQuery()->getMaxResults()
        );

        $count = $paginator->count();

        return new PaginatedResults(
            $paginator,
            $pageParameters->getPageNumber(),
            $totalPages,
            $pageParameters->getLimitPerPage(),
            $count
        );
    }
}
