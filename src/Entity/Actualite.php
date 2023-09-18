<?php

namespace App\Entity;
use DateTimeInterface;
use App\Repository\ActualiteRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ActualiteRepository::class)
 */
class Actualite
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $actu;

    /**
     * @ORM\Column(type="datetime")
     */
    private $Date_actu;

    /**
     * @ORM\Column(type="boolean")
     */
    private $affiche_actu;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $url;


    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getActu(): ?string
    {
        return $this->actu;
    }

    /**
     * @param string $actu
     * @return $this
     */
    public function setActu(string $actu): self
    {
        $this->actu = $actu;

        return $this;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getDateActu(): ?DateTimeInterface
    {
        return $this->Date_actu;
    }

    /**
     * @param DateTimeInterface $Date_actu
     * @return $this
     */
    public function setDateActu(DateTimeInterface $Date_actu): self
    {
        $this->Date_actu = $Date_actu;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getAfficheActu()
    {
        return $this->affiche_actu;
    }

    /**
     * @param mixed $affiche_actu
     */
    public function setAfficheActu($affiche_actu): void
    {
        $this->affiche_actu = $affiche_actu;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): self
    {
        $this->url = $url;

        return $this;
    }
}
