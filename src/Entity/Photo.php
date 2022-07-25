<?php

namespace App\Entity;

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
    private ?string $url = null;

    #[ORM\Column]
    private ?bool $estPrincipale = null;

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
}
