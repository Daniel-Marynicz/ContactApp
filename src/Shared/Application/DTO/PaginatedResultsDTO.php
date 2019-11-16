<?php

declare(strict_types=1);

namespace App\Shared\Application\DTO;

use JMS\Serializer\Annotation as JMS;
use Swagger\Annotations as SWG;

abstract class PaginatedResultsDTO
{
    /**
     * @var int
     * @JMS\Type("int")
     * @SWG\Property(
     *     type="int",
     *     example=3
     * )
     */

    private $page;

    /**
     * @var int
     * @JMS\Type("int")
     * @SWG\Property(
     *     type="int",
     *     example=10
     * )
     */
    private $totalPages;

    /**
     * @var int
     * @JMS\Type("int")
     * @SWG\Property(
     *     type="int",
     *     example=20
     * )
     */
    private $count;

    /**
     * @var int
     * @JMS\Type("int")
     * @SWG\Property(
     *     type="int",
     *     example=100
     * )
     */
    private $limit;

    public function __construct(int $page, int $totalPages, int $count, int $limit)
    {
        $this->page       = $page;
        $this->totalPages = $totalPages;
        $this->count      = $count;
        $this->limit      = $limit;
    }

    public function getPage() : int
    {
        return $this->page;
    }

    public function getTotalPages() : int
    {
        return $this->totalPages;
    }

    public function getCount() : int
    {
        return $this->count;
    }

    public function getLimit() : int
    {
        return $this->limit;
    }
}
