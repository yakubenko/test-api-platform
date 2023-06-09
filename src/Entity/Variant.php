<?php
declare(strict_types=1);

namespace App\Entity;

use App\Repository\VariantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: VariantRepository::class)]
#[ORM\Table(name: 'variants')]
class Variant
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['variant:read'])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'variants')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['variant:read', 'variant:write'])]
    private ?Product $product = null;

    #[ORM\Column(length: 255)]
    #[Groups(['variant:read', 'variant:write'])]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['variant:read', 'variant:write'])]
    private ?string $unit = null;

    #[ORM\OneToMany(mappedBy: 'variant', targetEntity: Price::class)]
    #[Groups(['variant:read'])]
    private Collection $prices;

    // It'll be STRING just for this test task. Later it can be presented as a bunch of fields
    // representing differet types or even be a relation to a dedicated UnitValue::class
    #[ORM\Column(length: 255)]
    #[Groups(['variant:read', 'variant:write'])]
    private ?string $unitValue = null;

    public function __construct()
    {
        $this->prices = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getUnit(): ?string
    {
        return $this->unit;
    }

    public function setUnit(?string $unit): self
    {
        $this->unit = $unit;

        return $this;
    }

    /**
     * @return Collection<int, Price>
     */
    public function getPrices(): Collection
    {
        return $this->prices;
    }

    public function addPrice(Price $price): self
    {
        if (!$this->prices->contains($price)) {
            $this->prices->add($price);
            $price->setVariant($this);
        }

        return $this;
    }

    public function removePrice(Price $price): self
    {
        if ($this->prices->removeElement($price)) {
            // set the owning side to null (unless already changed)
            if ($price->getVariant() === $this) {
                $price->setVariant(null);
            }
        }

        return $this;
    }

    public function getUnitValue(): ?string
    {
        return $this->unitValue;
    }

    public function setUnitValue(string $unitValue): self
    {
        $this->unitValue = $unitValue;

        return $this;
    }
}
