<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ClientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: ClientRepository::class)]
#[ApiResource(
    collectionOperations: [
        "get" => ["security" => "is_granted('ROLE_STATS')"],
    ],
    itemOperations: [
        "get" => ["security" => "is_granted('ROLE_STATS')"],
        ]
)]
class Client implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column()]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[
        Assert\NotBlank([
            'message' => 'client.email.not_blank',
        ]),
        Assert\Email([
            'message' => 'client.email.invalid',
        ]),
    ]
    private ?string $email = null;

    #[ORM\Column(length: 180, unique: true)]
    #[
        Assert\NotBlank([
            'message' => 'client.username.not_blank',
        ]),
        Assert\Length([
            'min' => 3,
            'max' => 180,
            'minMessage' => 'client.username.min_length',
            'maxMessage' => 'client.username.max_length',
        ]),
    ]
    private ?string $username = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    #[
        Assert\NotBlank([
            'message' => 'client.password.not_blank',
        ]),
        Assert\Length([
            'min' => 6,
            'max' => 255,
            'minMessage' => 'client.password.min_length',
            'maxMessage' => 'client.password.max_length',
        ]),
    ]
    private ?string $password = null;

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

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[
        Assert\NotBlank([
            'message' => 'client.date_naissance.not_blank',
        ]),
        Assert\LessThan([
            'value' => '-16 years',
            'message' => 'client.date_naissance.invalid',
        ]),
    ]
    private ?\DateTimeInterface $dateNaissance = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateInscription = null;

    #[ORM\ManyToMany(targetEntity: Produit::class, mappedBy: 'client')]
    private Collection $produits;

    #[ORM\ManyToOne(inversedBy: 'clients')]
    private ?Genre $genre = null;

    #[ORM\OneToMany(mappedBy: 'client', targetEntity: Avis::class)]
    private Collection $avis;

    #[ORM\ManyToOne(inversedBy: 'clients')]
    private ?Adresse $adresse = null;

    #[ORM\OneToMany(mappedBy: 'client', targetEntity: Panier::class)]
    private Collection $panier;

    public function __construct()
    {
        $this->produits = new ArrayCollection();
        $this->avis = new ArrayCollection();
        $this->panier = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }
    
//
    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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

    public function getDateNaissance(): ?\DateTimeInterface
    {
        return $this->dateNaissance;
    }

    public function setDateNaissance(\DateTimeInterface $dateNaissance): self
    {
        $this->dateNaissance = $dateNaissance;

        return $this;
    }

    public function getDateInscription(): ?\DateTimeInterface
    {
        return $this->dateInscription;
    }

    public function setDateInscription(\DateTimeInterface $dateInscription): self
    {
        $this->dateInscription = $dateInscription;

        return $this;
    }

    /**
     * @return Collection<int, Produit>
     */
    public function getProduits(): Collection
    {
        return $this->produits;
    }

    public function addProduit(Produit $produit): self
    {
        if (!$this->produits->contains($produit)) {
            $this->produits[] = $produit;
            $produit->addClient($this);
        }

        return $this;
    }

    public function removeProduit(Produit $produit): self
    {
        if ($this->produits->removeElement($produit)) {
            $produit->removeClient($this);
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

    /**
     * @return Collection<int, Avis>
     */
    public function getAvis(): Collection
    {
        return $this->avis;
    }

    public function addAvi(Avis $avi): self
    {
        if (!$this->avis->contains($avi)) {
            $this->avis[] = $avi;
            $avi->setClient($this);
        }

        return $this;
    }

    public function removeAvi(Avis $avi): self
    {
        if ($this->avis->removeElement($avi)) {
            // set the owning side to null (unless already changed)
            if ($avi->getClient() === $this) {
                $avi->setClient(null);
            }
        }

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

    /**
     * @return Collection<int, Panier>
     */
    public function getPanier(): Collection
    {
        return $this->panier;
    }

    public function addPanier(Panier $panier): self
    {
        if (!$this->panier->contains($panier)) {
            $this->panier[] = $panier;
            $panier->setClient($this);
        }

        return $this;
    }

    public function removePanier(Panier $panier): self
    {
        if ($this->panier->removeElement($panier)) {
            // set the owning side to null (unless already changed)
            if ($panier->getClient() === $this) {
                $panier->setClient(null);
            }
        }

        return $this;
    }
}
