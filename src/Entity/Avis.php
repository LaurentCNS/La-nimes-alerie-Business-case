<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\AvisRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AvisRepository::class)]
#[ApiResource(
    collectionOperations: [],
    itemOperations: [
        "get" => ["security" => "is_granted('ROLE_STATS')"],
        ]
)]
class Avis
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column()]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[
        Assert\NotBlank([
            'message' => 'avis.date.not_blank',
        ]),
    ]

    private ?\DateTimeInterface $dateAvis = null;

    #[ORM\Column(length: 500)]
    #[
        Assert\NotBlank([
            'message' => 'avis.commentaire.not_blank',
        ]),
        Assert\Length([
            'min' => 3,
            'max' => 500,
            'minMessage' => 'avis.commentaire.min_length',
            'maxMessage' => 'avis.commentaire.max_length',
        ]),     
    ]
    private ?string $description = null;

    #[ORM\Column]
    #[
        Assert\NotBlank([
            'message' => 'avis.note.not_blank',
        ]),
        Assert\GreaterThanOrEqual( 0 , 
            message: 'avis.note.greater_than_or_equal',
        ),
        Assert\LessThanOrEqual(10 ,
            message:'avis.note.less_than_or_equal',
        ),
    ]
    private ?int $note = null;

    #[ORM\ManyToOne(inversedBy: 'avis')]
    private ?Produit $produit = null;

    #[ORM\ManyToOne(inversedBy: 'avis')]
    private ?Client $client = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateAvis(): ?\DateTimeInterface
    {
        return $this->dateAvis;
    }

    public function setDateAvis(\DateTimeInterface $dateAvis): self
    {
        $this->dateAvis = $dateAvis;

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

    public function getNote(): ?int
    {
        return $this->note;
    }

    public function setNote(int $note): self
    {
        $this->note = $note;

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

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): self
    {
        $this->client = $client;

        return $this;
    }
}
