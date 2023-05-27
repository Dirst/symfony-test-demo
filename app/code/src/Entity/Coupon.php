<?php

namespace App\Entity;

use App\Repository\CouponRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Coupon
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $value = null;

    #[ORM\Column(type: "decimal", precision: 16, scale: 2, nullable: false)]
    private ?float $discount = null;

    #[ORM\Column]
    private ?bool $isFixedDiscount = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getDiscount(): ?float
    {
        return $this->discount;
    }

    public function setDiscount(float $discount): self
    {
        $this->discount = $discount;

        return $this;
    }

    public function isFixedDiscount(): ?bool
    {
        return $this->isFixedDiscount;
    }

    public function setIsFixedDiscount(bool $isFixedDiscount): self
    {
        $this->isFixedDiscount = $isFixedDiscount;

        return $this;
    }
}
