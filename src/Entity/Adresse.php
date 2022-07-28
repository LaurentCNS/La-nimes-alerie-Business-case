<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\AdresseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AdresseRepository::class)]
#[ApiResource(
    collectionOperations: [
        "get" => ["security" => "is_granted('ROLE_ADMIN')"],
    ],
    itemOperations: [
        "get" => ["security" => "is_granted('ROLE_ADMIN')"],
        ]
)]
class Adresse
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column()]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    #[
        Assert\NotBlank([
            'message' => 'adress.ligne.not_blank',
        ]),
        Assert\Length([
            'min' => 3,
            'max' => 50,
            'minMessage' => 'adress.ligne.min_length',
            'maxMessage' => 'adress.ligne.max_length',
        ]),     
    ]
    private ?string $ligne1 = null;

    #[ORM\Column(length: 50, nullable: true)]
    #[
        Assert\Length([
            'max' => 50,
            'maxMessage' => 'adress.ligne.max_length',
        ]),     
    ]
    private ?string $ligne2 = null;

    #[ORM\Column(length: 50, nullable: true)]
    #[
        Assert\Length([
            'max' => 50,
            'maxMessage' => 'adress.ligne.max_length',
        ]),     
    ]
    private ?string $ligne3 = null;

    #[ORM\Column(length: 10)]
    #[
        Assert\NotBlank([
            'message' => 'adress.code_postal.not_blank',
        ]),
        Assert\Length([
            'min' => 5,
            'max' => 10,
            'minMessage' => 'adress.code_postal.min_length',
            'maxMessage' => 'adress.code_postal.max_length',
        ]),     
    ]
    private ?string $codePostal = null;

    #[ORM\Column(length: 50)]
    #[
        Assert\NotBlank([
            'message' => 'adress.ville.not_blank',
        ]),
        Assert\Length([
            'min' => 3,
            'max' => 50,
            'minMessage' => 'adress.ville.min_length',
            'maxMessage' => 'adress.ville.max_length',
        ]),     
    ]
    private ?string $ville = null;

    #[ORM\Column(length: 50)]
    #[
        Assert\NotBlank([
            'message' => 'adress.pays.not_blank',
        ]),
        Assert\Length([
            'min' => 3,
            'max' => 50,
            'minMessage' => 'adress.pays.min_length',
            'maxMessage' => 'adress.pays.max_length',
        ]),     
    ]
    private ?string $pays = null;

    #[ORM\OneToMany(mappedBy: 'adresse', targetEntity: Client::class)]
    private Collection $clients;

    #[ORM\OneToMany(mappedBy: 'adresse', targetEntity: Panier::class)]
    private Collection $paniers;


    public function __construct()
    {
        $this->clients = new ArrayCollection();
        $this->paniers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLigne1(): ?string
    {
        return $this->ligne1;
    }

    public function setLigne1(string $ligne1): self
    {
        $this->ligne1 = $ligne1;

        return $this;
    }

    public function getLigne2(): ?string
    {
        return $this->ligne2;
    }

    public function setLigne2(?string $ligne2): self
    {
        $this->ligne2 = $ligne2;

        return $this;
    }

    public function getLigne3(): ?string
    {
        return $this->ligne3;
    }

    public function setLigne3(?string $ligne3): self
    {
        $this->ligne3 = $ligne3;

        return $this;
    }

    public function getCodePostal(): ?string
    {
        return $this->codePostal;
    }

    public function setCodePostal(string $codePostal): self
    {
        $this->codePostal = $codePostal;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(string $ville): self
    {
        $this->ville = $ville;

        return $this;
    }

    public function getPays(): ?string
    {
        return $this->pays;
    }

    public function setPays(string $pays): self
    {
        $this->pays = $pays;

        return $this;
    }

    /**
     * @return Collection<int, Client>
     */
    public function getClients(): Collection
    {
        return $this->clients;
    }

    public function addClient(Client $client): self
    {
        if (!$this->clients->contains($client)) {
            $this->clients[] = $client;
            $client->setAdresse($this);
        }

        return $this;
    }

    public function removeClient(Client $client): self
    {
        if ($this->clients->removeElement($client)) {
            // set the owning side to null (unless already changed)
            if ($client->getAdresse() === $this) {
                $client->setAdresse(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Panier>
     */
    public function getPaniers(): Collection
    {
        return $this->paniers;
    }

    public function addPanier(Panier $panier): self
    {
        if (!$this->paniers->contains($panier)) {
            $this->paniers[] = $panier;
            $panier->setAdresse($this);
        }

        return $this;
    }

    public function removePanier(Panier $panier): self
    {
        if ($this->paniers->removeElement($panier)) {
            // set the owning side to null (unless already changed)
            if ($panier->getAdresse() === $this) {
                $panier->setAdresse(null);
            }
        }

        return $this;
    }

}
