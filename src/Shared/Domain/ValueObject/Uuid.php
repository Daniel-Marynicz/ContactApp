<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValueObject;

use InvalidArgumentException;
use Ramsey\Uuid\Uuid as RamseyUuid;
use function sprintf;

abstract class Uuid
{
    /** @var string */
    private $value;

    public function __construct(string $value)
    {
        $this->ensureIsValidUuid($value);
        $this->value = $value;
    }

    public function value() : string
    {
        return $this->value;
    }

    /**
     * @return static
     */
    public static function generate() : Uuid
    {
        return new static(RamseyUuid::uuid4()->toString());
    }

    private function ensureIsValidUuid(string $uid) : void
    {
        if (! RamseyUuid::isValid($uid)) {
            throw new InvalidArgumentException(
                sprintf(
                    '<%s> does not allow the value <%s>.',
                    static::class,
                    $uid
                )
            );
        }
    }

    public function __toString() : string
    {
        return $this->value();
    }
}
