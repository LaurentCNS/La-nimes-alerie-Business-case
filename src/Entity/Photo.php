<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\PhotoRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PhotoRepository::class)]

class Photo
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column()]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    #[
        Assert\NotBlank([
            'message' => 'photo.url.not_blank',
        ]),
        Assert\length([
            'min' => 1,
            'max' => 100,
            'minMessage' => 'photo.url.min_length',
            'maxMessage' => 'photo.url.max_length',
        ]),

    ]
    private ?string $url = null;

    #[ORM\Column]
    private ?bool $estPrincipale = null;

    #[ORM\ManyToOne(inversedBy: 'photo')]
    private ?Produit $produit = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function isEstPrincipale(): ?bool
    {
        return $this->estPrincipale;
    }

    public function setEstPrincipale(bool $estPrincipale): self
    {
        $this->estPrincipale = $estPrincipale;

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
}
