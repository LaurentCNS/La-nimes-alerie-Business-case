<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\LigneRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LigneRepository::class)]
#[ApiResource(
    collectionOperations: [
        "get" => ["security" => "is_granted('ROLE_ADMIN')"],
    ],
    itemOperations: [
        "get" => ["security" => "is_granted('ROLE_ADMIN')"],
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
            'message' => 'ligne.numero.not_blank',
        ]),
        Assert\GreaterThanOrEqual( 0 ,
            message:'ligne.numero.greater_than_or_equal',
        ),
        Assert\LessThanOrEqual(99 ,
            message:'ligne.numero.less_than_or_equal',
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
}
