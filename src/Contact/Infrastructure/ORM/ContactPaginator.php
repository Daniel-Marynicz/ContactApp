<?php

declare(strict_types=1);

namespace App\Contact\Infrastructure\ORM;

use App\Contact\Domain\Model\Contact;
use App\Shared\Infrastructure\ORM\Pagination\Paginator;
use Countable;
use IteratorAggregate;

/**
 * @method Contact[] getIterator()
 */
class ContactPaginator extends Paginator implements Countable, IteratorAggregate
{
}
