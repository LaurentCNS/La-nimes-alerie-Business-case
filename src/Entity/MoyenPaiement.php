<?php

namespace App\Entity;

use App\Repository\MoyenPaiementRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MoyenPaiementRepository::class)]
class MoyenPaiement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column()]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $type = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }
}
