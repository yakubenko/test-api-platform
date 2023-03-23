<?php
declare(strict_types=1);

namespace App\Entity;

use App\Repository\CurrencyRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CurrencyRepository::class)]
#[ORM\Table(name: 'currencies')]
class Currency
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['currency:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['currency:read', 'currency:write'])]
    private ?string $name = null;

    #[ORM\Column(length: 32, unique: true)]
    #[Groups(['currency:read', 'currency:write'])]
    private ?string $code = null;

    #[ORM\Column(length: 8, unique: true)]
    #[Groups(['currency:read', 'currency:write'])]
    private ?string $sign = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getSign(): ?string
    {
        return $this->sign;
    }

    public function setSign(string $sign): self
    {
        $this->sign = $sign;

        return $this;
    }
}
