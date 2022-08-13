<?php

namespace App\Entity;

use App\Controller\stats\TotalProduitsVendusController;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\LigneRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LigneRepository::class)]
#[ApiResource(
    collectionOperations: [
        "get" => ["security" => "is_granted('ROLE_STATS')"],

    ],
    itemOperations: [
        "get" => ["security" => "is_granted('ROLE_STATS')"],
        ]
)]
class Ligne
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column()]
    private ?int $id = null;

    #[ORM\Column]
    #[
        Assert\NotBlank([
            'message' => 'ligne.quantite.not_blank',
        ]),
        Assert\GreaterThanOrEqual( 0 ,
            message:'ligne.quantite.greater_than_or_equal',
        ),
        Assert\LessThanOrEqual(99 ,
            message:'ligne.quantite.less_than_or_equal',
        ),
    ]
    private ?int $quantite = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    #[
        Assert\NotBlank([
            'message' => 'ligne.prix.not_blank',
        ]),
        Assert\GreaterThanOrEqual( 0 ,
            message:'ligne.prix.greater_than_or_equal',
        ),
    ]
    private ?string $prix = null;

    #[ORM\ManyToOne(inversedBy: 'ligne')]
    private ?Produit $produit = null;

    #[ORM\ManyToOne(inversedBy: 'ligne')]
    private ?Panier $panier = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 5, scale: 2)]
    private ?string $tva = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): self
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function getprix(): ?string
    {
        return $this->prix;
    }

    public function setprix(string $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getProduit(): ?Produit
    {
        return $this->produit;
    }

    public function setProduit(?Produit $produit): self
    {
        $this->produit = $produit;

        return $this;
    }

    public function getPanier(): ?Panier
    {
        return $this->panier;
    }

    public function setPanier(?Panier $panier): self
    {
        $this->panier = $panier;

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
}


//#[ORM\Column(length: 50)]
//    #[
//        Assert\NotBlank([
//            'message' => 'adresse.pays.not_blank',
//        ]),
//        Assert\Length([
//            'min' => 3,
//            'max' => 50,
//            'minMessage' => 'adresse.pays.min_length',
//            'maxMessage' => 'adresse.pays.max_length',
//        ]),
//    ]