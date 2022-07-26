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

    #[ORM\ManyToOne(inversedBy: 'commandes')]
    private ?MoyenPaiement $moyenPaiement = null;

    #[ORM\ManyToOne(inversedBy: 'commandes')]
    private ?Statut $statut = null;

    #[ORM\ManyToOne(inversedBy: 'commandes')]
    private ?Adresse $adresse = null;

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

    public function getMoyenPaiement(): ?MoyenPaiement
    {
        return $this->moyenPaiement;
    }

    public function setMoyenPaiement(?MoyenPaiement $moyenPaiement): self
    {
        $this->moyenPaiement = $moyenPaiement;

        return $this;
    }

    public function getStatut(): ?Statut
    {
        return $this->statut;
    }

    public function setStatut(?Statut $statut): self
    {
        $this->statut = $statut;

        return $this;
    }

    public function getAdresse(): ?Adresse
    {
        return $this->adresse;
    }

    public function setAdresse(?Adresse $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }
}
