<?php

namespace App\Entity;


use ApiPlatform\Core\Annotation\ApiResource;
use App\Controller\stats\NbCommandeController;
use App\Controller\stats\NbPanierController;
use App\Controller\stats\NbTotalVentesController;
use App\Repository\NbVisitesRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NbVisitesRepository::class)]
#[ApiResource(
    collectionOperations: [
        "get" => ["security" => "is_granted('ROLE_STATS')"],
    ],
    itemOperations: [
        "get" => ["security" => "is_granted('ROLE_STATS')"],
    ]
)]
class NbVisites
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column()]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateVisite = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateVisite(): ?\DateTimeInterface
    {
        return $this->dateVisite;
    }

    public function setDateVisite(\DateTimeInterface $dateVisite): self
    {
        $this->dateVisite = $dateVisite;

        return $this;
    }
}
