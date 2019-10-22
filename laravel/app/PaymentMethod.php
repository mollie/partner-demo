<?php declare(strict_types=1);

namespace App;

class PaymentMethod
{
    /** @var string */
    private $id;

    /** @var string */
    private $name;

    /** @var string */
    private $image;

    /** @var bool */
    private $active;

    public function __construct(string $id, string $name, string $image, bool $active)
    {
        $this->id = $id;
        $this->name = $name;
        $this->image = $image;
        $this->active = $active;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getImage(): string
    {
        return $this->image;
    }

    public function isActive(): bool
    {
        return $this->active;
    }
}
