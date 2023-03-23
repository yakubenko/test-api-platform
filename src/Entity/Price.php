<?php
declare(strict_types=1);

namespace App\Entity;

use App\Repository\PriceRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: PriceRepository::class)]
#[ORM\Table(name: 'prices')]
class Price
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['price:read'])]
    private ?int $id = null;

    // As this is just a check task, I use the simplest option
    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    #[Groups(['price:read', 'price:write'])]
    private ?string $price = null;

    #[ORM\ManyToOne]
    #[Groups(['price:read', 'price:write'])]
    private ?Currency $currency = null;

    #[ORM\ManyToOne(inversedBy: 'prices')]
    #[Groups(['price:read', 'price:write'])]
    private ?Variant $variant = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getCurrency(): ?Currency
    {
        return $this->currency;
    }

    public function setCurrency(?Currency $currency): self
    {
        $this->currency = $currency;

        return $this;
    }

    public function getVariant(): ?Variant
    {
        return $this->variant;
    }

    public function setVariant(?Variant $variant): self
    {
        $this->variant = $variant;

        return $this;
    }
}
