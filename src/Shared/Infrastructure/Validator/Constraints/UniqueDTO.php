<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 * @Target({"PROPERTY"})
 */
class UniqueDTO extends Constraint
{
    /** @var string */
    public $entityClass;
    /** @var string */
    public $entityPropertyName;
    /** @var string */
    public $requestKey = 'uuid';
    /** @var string */
    public $uuidFieldName = 'uuid';

    /** @var string */
    public $message = 'The record "{{ string }}" already exits in db.';

    public function getTargets() : string
    {
        return Constraint::PROPERTY_CONSTRAINT;
    }
}
