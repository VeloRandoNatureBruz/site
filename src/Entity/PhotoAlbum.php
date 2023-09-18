<?php

namespace App\Entity;

use App\Repository\PhotoAlbumRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PhotoAlbumRepository::class)
 */
class PhotoAlbum
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * @ORM\Column (type="text", nullable=true)
     */
    private $url;

    /**
     * @ORM\ManyToOne (targetEntity=Activite::class, inversedBy="albumPhoto")
     */
    private $activite;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setImage($image): void
    {
        $this->image = $image;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function setUrl($url): void
    {
        $this->url = $url;
    }

    public function getActivite()
    {
        return $this->activite;
    }

    public function setActivite($activite): void
    {
        $this->activite = $activite;
    }

    public function getCategorie(): ?categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?categorie $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }

    public function getCategoriesFormation(): ?CategorieFormation
    {
        return $this->categories_formation;
    }

    public function setCategoriesFormation(?CategorieFormation $categories_formation): self
    {
        $this->categories_formation = $categories_formation;

        return $this;
    }



}
