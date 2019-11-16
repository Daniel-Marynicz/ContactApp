<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class ContactEmail
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     *
     * @var int
     */
    private $id;

    /**
     * @ORM\Column(nullable=false)
     *
     * @var string
     */
    private $value;

    /**
     * @ORM\Column(nullable=true)
     *
     * @var string|null
     */
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
