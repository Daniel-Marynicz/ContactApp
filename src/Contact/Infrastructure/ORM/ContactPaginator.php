<?php

declare(strict_types=1);

namespace App\Contact\Infrastructure\ORM;

use App\Contact\Domain\Model\Contact;
use App\Shared\Infrastructure\ORM\Pagination\Paginator;

/**
 * @method Contact[] getIterator()
 */
class ContactPaginator extends Paginator
{
}
