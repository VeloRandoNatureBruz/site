<?php

namespace App\Entity;

use App\Entity\Activite;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity(repositoryClass: 'App\Repository\UserRepository')]
#[UniqueEntity(fields: ['username', 'email'], message: 'Il y a déjà un compte avec ce pseudo ou  cet email')]

class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[Assert\Length(max: 50, maxMessage: 'Maximum 50 caracteres')]
    #[ORM\Column(type: 'string', length: 50, unique: true)]
    private $username;

    #[ORM\Column(type: 'json')]
    private $roles = [];

    /**
     * @var string The hashed password
     */
    
    #[ORM\Column(type: 'string')]
    private ?string $password;

    #[Assert\Type('string')]
    #[Assert\NotBlank(message: 'Veuillez inscrire votre nom')]
    #[ORM\Column(type: 'string', length: 60)]
    private $nom;

    #[ORM\Column(type: 'string', length: 60)]
    private $prenom;

    #[ORM\Column(type: 'string', length: 20, nullable: true)]
    private $telephone;

    #[Assert\Email()]
    #[ORM\Column(type: 'string', length: 255, unique : true)]
    private $email;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private $date_naissance;

    #[ORM\Column(type: 'string', length: 100, nullable: true)]
    private $resetToken;

    #[ORM\ManyToMany(targetEntity: Activite::class, inversedBy: 'users')]
    private $inscription;

    #[ORM\OneToMany(targetEntity: Activite::class, mappedBy: 'organisateur', cascade: ['remove'])]
    private $activite;

    #[ORM\OneToMany(targetEntity: Photo::class, mappedBy: 'adhherent', cascade: ['persist', 'remove'])]
    private $photos;


    #[ORM\OneToOne(mappedBy: 'user', cascade: ['persist', 'remove'])]
    private ?Bureau $bureau = null;

    #[ORM\ManyToMany(targetEntity: Referent::class, inversedBy: 'users')]
    private Collection $referents;

    public function __toString()
    {
        return $this->getUserIdentifier();
    }

    public function __construct()
    {
        $this->inscription = new ArrayCollection();
        $this->activite = new ArrayCollection();
        $this->photos = new ArrayCollection();
        $this->referents = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getUsername(): string
    {
        return (string) $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
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
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        return null;
        // not needed when using the 'bcrypt' algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
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

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getDateNaissance(): ?\DateTimeInterface
    {
        return $this->date_naissance;
    }

    public function setDateNaissance(?\DateTimeInterface $date_naissance): self
    {
        $this->date_naissance = $date_naissance;

        return $this;
    }

    public function getResetToken(): ?string
    {
        return $this->resetToken;
    }

    public function setResetToken(?string $resetToken): self
    {
        $this->resetToken = $resetToken;

        return $this;
    }

    public function getReferents(): Collection
    {
        return $this->referents;
    }

    public function setReferents(Collection $referents): self
    {
        $this->referents = $referents;

        return $this;
    }



    /**
     * @return Collection|Activite[]
     */
    public function getInscription(): Collection
    {
        return $this->inscription;
    }

    public function addInscription(Activite $inscription): self
    {
        if (!$this->inscription->contains($inscription)) {
            $this->inscription[] = $inscription;
        }

        return $this;
    }

    public function removeInscription(Activite $inscription): self
    {
        $this->inscription->removeElement($inscription);

        return $this;
    }

    /**
     * @return Collection|Activite[]
     */
    public function getActivite(): Collection
    {
        return $this->activite;
    }

    public function addActivite(Activite $activite): self
    {
        if (!$this->activite->contains($activite)) {
            $this->activite[] = $activite;
            $activite->setOrganisateur($this);
        }

        return $this;
    }

    public function removeActivite(Activite $activite): self
    {
        if ($this->activite->removeElement($activite)) {
            // set the owning side to null (unless already changed)
            if ($activite->getOrganisateur() === $this) {
                $activite->setOrganisateur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Photo[]
     */
    public function getPhotos(): Collection
    {
        return $this->photos;
    }

    public function addPhoto(Photo $photo): self
    {
        if (!$this->photos->contains($photo)) {
            $this->photos[] = $photo;
            $photo->setAdhherent($this);
        }

        return $this;
    }

    public function removePhoto(Photo $photo): self
    {
        if ($this->photos->removeElement($photo)) {
            // set the owning side to null (unless already changed)
            if ($photo->getAdhherent() === $this) {
                $photo->setAdhherent(null);
            }
        }

        return $this;
    }

    public function getBureau(): ?Bureau
    {
        return $this->bureau;
    }

    public function setBureau(?Bureau $bureau): static
    {
        // unset the owning side of the relation if necessary
        if ($bureau === null && $this->bureau !== null) {
            $this->bureau->setUser(null);
        }

        // set the owning side of the relation if necessary
        if ($bureau !== null && $bureau->getUser() !== $this) {
            $bureau->setUser($this);
        }

        $this->bureau = $bureau;

        return $this;
    }

    public function addReferent(Referent $referent): static
    {
        if (!$this->referents->contains($referent)) {
            $this->referents->add($referent);
        }

        return $this;
    }

    public function removeReferent(Referent $referent): static
    {
        $this->referents->removeElement($referent);

        return $this;
    }
}
