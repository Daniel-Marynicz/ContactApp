<?php


namespace App\Services\Uuid;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class UuidProvider
{
    public static function generate() : UuidInterface
    {
        return Uuid::uuid4();
    }

    public static function fromString(string $value) : UuidInterface
    {
        return Uuid::fromString($value);
    }
}
