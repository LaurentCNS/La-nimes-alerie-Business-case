<?php

namespace App\Entity;

use App\Controller\stats\TotalProduitsVendusController;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ProduitRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProduitRepository::class)]
#[ApiResource(
    collectionOperations: [
        "get_TotalProduitsVendus" => ["security" => "is_granted('ROLE_STATS')",
            "method" => "GET",
            "path" => "/TotalProduitsVendus",
            "parameters" => [
                [
                    "name" => "dateDebut",
                    "in" => "query",
                    "description" => "Date de début",
                    "required" => true,
                    "schema" => [
                        "type" => "string",
                        "format" => "date"
                    ]
                ],
                [
                    "name" => "dateFin",
                    "in" => "query",
                    "description" => "Date de fin",
                    "required" => true,
                    "schema" => [
                        "type" => "string",
                        "format" => "date"
                    ]
                ]
            ],
            "defaults" => ["_format" => "json"],
            "controller" => TotalProduitsVendusController::class
        ],
    ],
    itemOperations: [
        "get" => ["security" => "is_granted('ROLE_STATS')"],
        ]
)]
class Produit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column()]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    #[
        Assert\NotBlank([
            'message' => 'produit.libelle.not_blank',
        ]),
        Assert\Length([
            'min' => 3,
            'max' => 100,
            'minMessage' => 'produit.libelle.min_length',
            'maxMessage' => 'produit.libelle.max_length',
        ]),     
    ]
    private ?string $libelle = null;

    #[ORM\Column(length: 500)]
    #[
        Assert\NotBlank([
            'message' => 'produit.description.not_blank',
        ]),
        Assert\Length([
            'min' => 3,
            'max' => 500,
            'minMessage' => 'produit.description.min_length',
            'maxMessage' => 'produit.description.max_length',
        ]),     
    ]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateEntree = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    #[
        Assert\NotBlank([
            'message' => 'produit.prix.not_blank',
        ]),
        Assert\GreaterThanOrEqual([
            'value' => 0,
            'message' => 'produit.prix.greater_than_or_equal',
        ]),
    ]
    private ?string $prixHt = null;

    #[ORM\Column]
    private ?bool $estActif = null;

    #[ORM\ManyToOne(inversedBy: 'produits')]
    private ?Marque $marque = null;

    #[ORM\OneToMany(mappedBy: 'produit', targetEntity: Photo::class)]
    private Collection $photo;

    #[ORM\ManyToOne(inversedBy: 'produits')]
    private ?Promotion $promotion = null;

    #[ORM\ManyToOne(inversedBy: 'produits')]
    private ?Categorie $categorie = null;

    #[ORM\OneToMany(mappedBy: 'produit', targetEntity: Avis::class)]
    private Collection $avis;

    #[ORM\OneToMany(mappedBy: 'produit', targetEntity: Ligne::class)]
    private Collection $ligne;

    #[ORM\ManyToMany(targetEntity: Client::class, inversedBy: 'produits')]
    private Collection $client;

    #[ORM\Column(type: Types::DECIMAL, precision: 5, scale: 2)]
    #[
        Assert\NotBlank([
            'message' => 'produit.tva.not_blank',
        ]),
        Assert\GreaterThanOrEqual([
            'value' => 0,
            'message' => 'produit.tva.greater_than_or_equal',
        ]),
    ]
    private ?string $tva = null;

    #[ORM\Column]
    private ?int $quantiteStock = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $slug = null;

    #[ORM\Column(length: 50)]
    private ?string $resume = null;

    public function __construct()
    {
        $this->photo = new ArrayCollection();
        $this->avis = new ArrayCollection();
        $this->ligne = new ArrayCollection();
        $this->client = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDateEntree(): ?\DateTimeInterface
    {
        return $this->dateEntree;
    }

    public function setDateEntree(\DateTimeInterface $dateEntree): self
    {
        $this->dateEntree = $dateEntree;

        return $this;
    }

    public function getPrixHt(): ?string
    {
        return $this->prixHt;
    }

    public function setPrixHt(string $prixHt): self
    {
        $this->prixHt = $prixHt;

        return $this;
    }

    public function isEstActif(): ?bool
    {
        return $this->estActif;
    }

    public function setEstActif(bool $estActif): self
    {
        $this->estActif = $estActif;

        return $this;
    }

    public function getMarque(): ?Marque
    {
        return $this->marque;
    }

    public function setMarque(?Marque $marque): self
    {
        $this->marque = $marque;

        return $this;
    }

    /**
     * @return Collection<int, Photo>
     */
    public function getPhoto(): Collection
    {
        return $this->photo;
    }

    public function getPhotoPrincipale(): ?Photo
    {
        foreach ($this->photo as $photo) {
            if ($photo->isEstPrincipale()) {
                return $photo;
            }
        }
        return null;
    }


    public function addPhoto(Photo $photo): self
    {
        if (!$this->photo->contains($photo)) {
            $this->photo[] = $photo;
            $photo->setProduit($this);
        }

        return $this;
    }

    public function removePhoto(Photo $photo): self
    {
        if ($this->photo->removeElement($photo)) {
            // set the owning side to null (unless already changed)
            if ($photo->getProduit() === $this) {
                $photo->setProduit(null);
            }
        }

        return $this;
    }

    public function getPromotion(): ?Promotion
    {
        return $this->promotion;
    }

    public function setPromotion(?Promotion $promotion): self
    {
        $this->promotion = $promotion;

        return $this;
    }

    public function getPourcentagePromotion(): ?int
    {
        if ($this->promotion) {
            return $this->promotion->getPourcentage();
        }
        return null;
    }

    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }

    /**
     * @return Collection<int, Avis>
     */
    public function getAvis(): Collection
    {
        return $this->avis;
    }

    // récuperer la moyenne des notes des avis
    public function getAverageNote($avis): ?float
    {
        $moyenne = 0;
        $nbAvis = 0;
        foreach ($this->avis as $avis) {
            $moyenne += $avis->getNote();
            $nbAvis++;
        }
        if ($nbAvis > 0) {
            $moyenne /= $nbAvis;
        }
        return $moyenne;
    }

    public function addAvi(Avis $avi): self
    {
        if (!$this->avis->contains($avi)) {
            $this->avis[] = $avi;
            $avi->setProduit($this);
        }

        return $this;
    }

    public function removeAvi(Avis $avi): self
    {
        if ($this->avis->removeElement($avi)) {
            // set the owning side to null (unless already changed)
            if ($avi->getProduit() === $this) {
                $avi->setProduit(null);
            }
        }

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
            $ligne->setProduit($this);
        }

        return $this;
    }

    public function removeLigne(Ligne $ligne): self
    {
        if ($this->ligne->removeElement($ligne)) {
            // set the owning side to null (unless already changed)
            if ($ligne->getProduit() === $this) {
                $ligne->setProduit(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Client>
     */
    public function getClient(): Collection
    {
        return $this->client;
    }

    public function addClient(Client $client): self
    {
        if (!$this->client->contains($client)) {
            $this->client[] = $client;
        }

        return $this;
    }

    public function removeClient(Client $client): self
    {
        $this->client->removeElement($client);

        return $this;
    }

    public function getTva(): ?string
    {
        return $this->tva;
    }

    public function setTva(string $tva): self
    {
        $this->tva = $tva;

        return $this;
    }

    public function getQuantiteStock(): ?int
    {
        return $this->quantiteStock;
    }

    public function setQuantiteStock(int $quantiteStock): self
    {
        $this->quantiteStock = $quantiteStock;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getResume(): ?string
    {
        return $this->resume;
    }

    public function setResume(string $resume): self
    {
        $this->resume = $resume;

        return $this;
    }


}