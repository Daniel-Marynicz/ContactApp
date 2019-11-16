<?php

declare(strict_types=1);

namespace App\Contact\Domain\Model;

class ContactEmail
{
    /** @var int */
    private $id;

    /** @var string */
    private $value;

    /** @var string|null */
    private $label;

    public function __construct(string $value, ?string $label = null)
    {
        $this->value = $value;
        $this->label = $label;
    }

    public function getId() : int
    {
        return $this->id;
    }

    public function setId(int $id) : ContactEmail
    {
        $this->id = $id;

        return $this;
    }

    public function getValue() : string
    {
        return $this->value;
    }

    public function setValue(string $value) : ContactEmail
    {
        $this->value = $value;

        return $this;
    }

    public function getLabel() : ?string
    {
        return $this->label;
    }

    public function setLabel(?string $label) : ContactEmail
    {
        $this->label = $label;

        return $this;
    }
}
