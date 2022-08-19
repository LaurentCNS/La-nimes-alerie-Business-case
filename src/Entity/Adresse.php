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
        "get" => ["security" => "is_granted('ROLE_STATS')"],
    ],
    itemOperations: [
        "get" => ["security" => "is_granted('ROLE_STATS')"],
        ]
)]
class Adresse
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column()]
    private ?int $id = null;

    #[ORM\Column(length: 30)]
    #[
        Assert\NotBlank([
            'message' => 'client.nom.not_blank',
        ]),
        Assert\Length([
            'min' => 3,
            'max' => 30,
            'minMessage' => 'client.nom.min_length',
            'maxMessage' => 'client.nom.max_length',
        ]),
    ]
    private ?string $nom = null;

    #[ORM\Column(length: 30)]
    #[
        Assert\NotBlank([
            'message' => 'client.prenom.not_blank',
        ]),
        Assert\Length([
            'min' => 3,
            'max' => 30,
            'minMessage' => 'client.prenom.min_length',
            'maxMessage' => 'client.prenom.max_length',
        ]),
    ]
    private ?string $prenom = null;

    #[ORM\Column(length: 50)]
    #[
        Assert\NotBlank([
            'message' => 'adresse.ligne.not_blank',
        ]),
        Assert\Length([
            'min' => 3,
            'max' => 50,
            'minMessage' => 'adresse.ligne.min_length',
            'maxMessage' => 'adresse.ligne.max_length',
        ]),
    ]
    private ?string $ligne1 = null;

    #[ORM\Column(length: 50, nullable: true)]
    #[
        Assert\Length([
            'max' => 50,
            'maxMessage' => 'adresse.ligne.max_length',
        ]),     
    ]
    private ?string $ligne2 = null;


    #[ORM\Column(length: 50, nullable: true)]
    #[
        Assert\Length([
            'max' => 50,
            'maxMessage' => 'adresse.ligne.max_length',
        ]),     
    ]
    private ?string $ligne3 = null;

    #[ORM\Column(length: 10)]
    #[
        Assert\NotBlank([
            'message' => 'adresse.code_postal.not_blank',
        ]),
        Assert\Length([
            'min' => 5,
            'max' => 10,
            'minMessage' => 'adresse.code_postal.min_length',
            'maxMessage' => 'adresse.code_postal.max_length',
        ]),     
    ]
    private ?string $codePostal = null;

    #[ORM\Column(length: 50)]
    #[
        Assert\NotBlank([
            'message' => 'adresse.ville.not_blank',
        ]),
        Assert\Length([
            'min' => 3,
            'max' => 50,
            'minMessage' => 'adresse.ville.min_length',
            'maxMessage' => 'adresse.ville.max_length',
        ]),     
    ]
    private ?string $ville = null;

    #[ORM\Column(length: 50)]
    #[
        Assert\NotBlank([
            'message' => 'adresse.pays.not_blank',
        ]),
        Assert\Length([
            'min' => 3,
            'max' => 50,
            'minMessage' => 'adresse.pays.min_length',
            'maxMessage' => 'adresse.pays.max_length',
        ]),
    ]
    private ?string $pays = null;


    #[ORM\OneToMany(mappedBy: 'adresse', targetEntity: Panier::class)]
    private Collection $paniers;

    #[ORM\ManyToOne(inversedBy: 'adresses')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Genre $genre = null;

    #[ORM\ManyToOne(inversedBy: 'adresses')]


    #[ORM\Column(length: 10)]
    private ?string $telephone = null;

    #[ORM\ManyToOne(inversedBy: 'adresses')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Client $client = null;

    #[ORM\Column]
    private ?bool $estPrincipale = null;



    public function __construct()
    {
        $this->paniers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
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

    public function getGenre(): ?Genre
    {
        return $this->genre;
    }

    public function setGenre(?Genre $genre): self
    {
        $this->genre = $genre;

        return $this;
    }



    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): self
    {
        $this->telephone = $telephone;

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
