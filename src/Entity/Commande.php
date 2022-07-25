<?php

namespace App\Entity;

use App\Repository\CommandeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommandeRepository::class)]
class Commande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column()]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateConversion = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateFacturation = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateConversion(): ?\DateTimeInterface
    {
        return $this->dateConversion;
    }

    public function setDateConversion(\DateTimeInterface $dateConversion): self
    {
        $this->dateConversion = $dateConversion;

        return $this;
    }

    public function getDateFacturation(): ?\DateTimeInterface
    {
        return $this->dateFacturation;
    }

    public function setDateFacturation(\DateTimeInterface $dateFacturation): self
    {
        $this->dateFacturation = $dateFacturation;

        return $this;
    }
}
