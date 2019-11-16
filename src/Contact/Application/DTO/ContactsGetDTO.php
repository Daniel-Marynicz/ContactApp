<?php

declare(strict_types=1);

namespace App\Contact\Application\DTO;

use App\Shared\Application\DTO\PaginatedResultsDTO;
use JMS\Serializer\Annotation as JMS;

class ContactsGetDTO extends PaginatedResultsDTO
{
    /**
     * @var ContactGetDTO[]
     * @JMS\Type("array<App\Contact\Application\DTO\ContactGetDTO>")
     */
    private $contacts = [];

    /**
     * @param ContactGetDTO[] $contacts
     */
    public function __construct(int $page, int $totalPages, int $count, int $limit, array $contacts)
    {
        parent::__construct($page, $totalPages, $count, $limit);
        $this->contacts = $contacts;
    }

    /**
     * @return ContactGetDTO[]
     */
    public function getContacts() : array
    {
        return $this->contacts;
    }
}
