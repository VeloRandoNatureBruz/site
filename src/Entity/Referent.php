<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ReferentRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: ReferentRepository::class)]

class Referent
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 75)]
    private $nom;

    #[ORM\Column (type: 'integer')]
    private $ordre;

    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'referents')]
    private Collection $users;



    public function __toString()
    {
        return $this->getNom();
    }

    public function __construct()
    {
        $this->users = new ArrayCollection();
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

    public function getOrdre()
    {
        return $this->ordre;
    }

    public function setOrdre($ordre): void
    {
        $this->ordre = $ordre;
    }


    public function getUsers(): Collection
    {
        return $this->users;
    }
    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            // optionally set the owning side to null (unless already changed)
            if ($user->getReferents() !== $this) {
                $user->addReferent($this);
            }
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getReferents() === $this) {
                $user->removeReferent($this);
            }
        }

        return $this;
    }
}


