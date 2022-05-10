<?php

namespace App\Entity;

use App\Entity\Utilisateurs;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\BoutiqueRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=BoutiqueRepository::class)
 */
class Boutique
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     *  @Assert\NotBlank(message="Nom Boutique is required")
     */
    private $nomBoutique;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="description is required")

     */
    private $descBoutique;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="adresse is required")
     */
    private $adresseBoutique;

    /**
     * @ORM\OneToMany(targetEntity=Produit::class, mappedBy="boutique" , cascade={"persist", "remove"})
     */
    private $produit;

    /**
     * @ORM\OneToOne(targetEntity=Utilisateurs::class)
     */
    private $Commercant;

    /**
     * @ORM\OneToMany(targetEntity=Images::class, mappedBy="boutique", cascade={"persist", "remove"})   
     */
    private $images;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $latitude;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $longitude;

    public function __construct()
    {
        $this->produit = new ArrayCollection();
        $this->commercant = new ArrayCollection();
        $this->utilisateurs = new ArrayCollection();
        $this->images = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomBoutique(): ?string
    {
        return $this->nomBoutique;
    }

    public function setNomBoutique(string $nomBoutique): self
    {
        $this->nomBoutique = $nomBoutique;

        return $this;
    }

    public function getDescBoutique(): ?string
    {
        return $this->descBoutique;
    }

    public function setDescBoutique(string $descBoutique): self
    {
        $this->descBoutique = $descBoutique;

        return $this;
    }

    public function getAdresseBoutique(): ?string
    {
        return $this->adresseBoutique;
    }

    public function setAdresseBoutique(string $adresseBoutique): self
    {
        $this->adresseBoutique = $adresseBoutique;

        return $this;
    }

    /**
     * @return Collection|Produit[]
     */
    public function getProduit(): Collection
    {
        return $this->produit;
    }

    public function addProduit(Produit $produit): self
    {
        if (!$this->produit->contains($produit)) {
            $this->produit[] = $produit;
            $produit->setBoutique($this);
        }

        return $this;
    }

    public function removeProduit(Produit $produit): self
    {
        if ($this->produit->removeElement($produit)) {
            // set the owning side to null (unless already changed)
            if ($produit->getBoutique() === $this) {
                $produit->setBoutique(null);
            }
        }

        return $this;
    }

    public function getCommercant(): ?Utilisateurs
    {
        return $this->Commercant;
    }

    public function setCommercant(?Utilisateurs $Commercant): self
    {
        $this->Commercant = $Commercant;

        return $this;
    }

    /**
     * @return Collection<int, Images>
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Images $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
            $image->setBoutique($this);
        }

        return $this;
    }

    public function removeImage(Images $image): self
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getBoutique() === $this) {
                $image->setBoutique(null);
            }
        }

        return $this;
    }

    public function getLatitude(): ?string
    {
        return $this->latitude;
    }

    public function setLatitude(string $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?string
    {
        return $this->longitude;
    }

    public function setLongitude(string $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }

   
    
   

   
}
