<?php

namespace App\Entity;

use App\Controller\stats\ConversionCommandesController;
use App\Controller\stats\ConversionPaniersController;
use App\Controller\stats\PanierMoyenController;
use App\Controller\stats\PourcentagePanierAbandonnéesController;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Controller\stats\NbTotalVentesController;
use App\Controller\stats\NbCommandeController;
use App\Controller\stats\NbPanierController;
use App\Enum\EnumStatutPanier;
use App\Repository\PanierRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PanierRepository::class)]
#[ApiResource(
    collectionOperations: [
        "get" => ["security" => "is_granted('ROLE_STATS')"], 
        "get_nbTotalVentes" => ["security" => "is_granted('ROLE_STATS')",
            "method" => "GET",
            "path" => "/panier/MontantTotalVentes",
            "defaults" => ["_format" => "json"],
            "controller" => NbTotalVentesController::class,
        ],
        "get_nbCommandes" => ["security" => "is_granted('ROLE_STATS')",
            "method" => "GET",
            "path" => "/panier/nbCommandes",
            "defaults" => ["_format" => "json"],
            "controller" => NbCommandeController::class,
        ],
        "get_nbPaniers" => ["security" => "is_granted('ROLE_STATS')",
            "method" => "GET",
            "path" => "/panier/nbPaniers",
            "defaults" => ["_format" => "json"],
            "controller" => NbPanierController::class,
        ],
        "get_valeurPanierMoyen" => ["security" => "is_granted('ROLE_STATS')",
            "method" => "GET",
            "path" => "/panier/valeurPanierMoyen",
            "defaults" => ["_format" => "json"],
            "controller" => PanierMoyenController::class
        ],
        "get_pourcantagePanierAbandonnées" => ["security" => "is_granted('ROLE_STATS')",
            "method" => "GET",
            "path" => "/panier/pourcentagePanierAbandonnées",
            "defaults" => ["_format" => "json"],
            "controller" => PourcentagePanierAbandonnéesController::class
        ],
        "get_conversionPaniers" => ["security" => "is_granted('ROLE_STATS')",
            "method" => "GET",
            "path" => "/panier/conversionPaniers",
            "defaults" => ["_format" => "json"],
            "controller" => ConversionPaniersController::class
        ],
        "get_conversionCommande" => ["security" => "is_granted('ROLE_STATS')",
            "method" => "GET",
            "path" => "/panier/conversionCommande",
            "defaults" => ["_format" => "json"],
            "controller" => ConversionCommandesController::class
        ],

        
    ],
    itemOperations: [
        "get" => ["security" => "is_granted('ROLE_STATS')"],
        ]
)]
class Panier
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column()]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[
        Assert\NotBlank([
            'message' => 'panier.date_creation.not_blank',
        ]),
    ]
    private ?\DateTimeInterface $dateCreation = null;

    #[ORM\Column]
    #[
        Assert\NotBlank([
            'message' => 'panier.statut.not_blank',
        ]),
    ]

    private int $statut = EnumStatutPanier::CREEE;

    #[ORM\ManyToOne(inversedBy: 'panier')]
    private ?Client $client = null;

    #[ORM\OneToMany(mappedBy: 'panier', targetEntity: Ligne::class)]
    private Collection $ligne;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $datePaiement = null;

    #[ORM\ManyToOne(inversedBy: 'paniers')]
    private ?Adresse $adresse = null;

    #[ORM\ManyToOne(inversedBy: 'paniers')]
    private ?MoyenPaiement $moyenPaiement = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $numeroCommande = null;



    public function __construct()
    {
        $this->ligne = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->dateCreation;
    }

    public function setDateCreation(\DateTimeInterface $dateCreation): self
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    public function getstatut(): ?int
    {
        return $this->statut;
    }

    public function setstatut(int $statut): self
    {
        $this->statut = $statut;

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): self
    {
        $this->client = $client;

        return $this;
    }

    /**
     * @return Collection<int, Ligne>
     */
    public function getLigne(): Collection
    {
        return $this->ligne;
    }

    public function addLigne(Ligne $ligne): self
    {
        if (!$this->ligne->contains($ligne)) {
            $this->ligne[] = $ligne;
            $ligne->setPanier($this);
        }

        return $this;
    }

    public function removeLigne(Ligne $ligne): self
    {
        if ($this->ligne->removeElement($ligne)) {
            // set the owning side to null (unless already changed)
            if ($ligne->getPanier() === $this) {
                $ligne->setPanier(null);
            }
        }

        return $this;
    }

    public function getDatePaiement(): ?\DateTimeInterface
    {
        return $this->datePaiement;
    }

    public function setDatePaiement(?\DateTimeInterface $datePaiement): self
    {
        $this->datePaiement = $datePaiement;

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

    public function getMoyenPaiement(): ?MoyenPaiement
    {
        return $this->moyenPaiement;
    }

    public function setMoyenPaiement(?MoyenPaiement $moyenPaiement): self
    {
        $this->moyenPaiement = $moyenPaiement;

        return $this;
    }

    public function getNumeroCommande(): ?string
    {
        return $this->numeroCommande;
    }

    public function setNumeroCommande(?string $numeroCommande): self
    {
        $this->numeroCommande = $numeroCommande;

        return $this;
    }



}
