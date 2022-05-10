<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ProduitRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ProduitRepository::class)
 */
class Produit
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("produit:read")
     * @Groups("commande:read")
     */
    private $id;

    /**
     * @ORM\Column(type="float")
     * @Assert\NotBlank(message="prix is required")
     * @Groups("produit:read")
     */
    private $prixProduit;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(message="quantite is required")
     * @Groups("produit:read")
     */
    private $quantiteProduit;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="description is required")
     * @Groups("produit:read")
     */
    private $descProduit;

    /**
     * @ORM\Column(type="string", length=255 )
     * @Groups("produit:read")
     * @Groups("commande:read")
     */
    private $image;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Nom Produit is required")
     * @Groups("produit:read")
     * @Groups("commande:read")
     */
    private $nomProduit;

    /**
     * @ORM\ManyToOne(targetEntity=Boutique::class, inversedBy="produit")
     */
    private $boutique;

    /**
     * @ORM\OneToMany(targetEntity=Note::class, mappedBy="produit")
     */
    private $notes;

    public function __construct()
    {
        $this->notes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrixProduit(): ?float
    {
        return $this->prixProduit;
    }

    public function setPrixProduit(float $prixProduit): self
    {
        $this->prixProduit = $prixProduit;

        return $this;
    }

    public function getQuantiteProduit(): ?int
    {
        return $this->quantiteProduit;
    }

    public function setQuantiteProduit(int $quantiteProduit): self
    {
        $this->quantiteProduit = $quantiteProduit;

        return $this;
    }

    public function getDescProduit(): ?string
    {
        return $this->descProduit;
    }

    public function setDescProduit(string $descProduit): self
    {
        $this->descProduit = $descProduit;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getNomProduit(): ?string
    {
        return $this->nomProduit;
    }

    public function setNomProduit(string $nomProduit): self
    {
        $this->nomProduit = $nomProduit;

        return $this;
    }

    public function getBoutique(): ?Boutique
    {
        return $this->boutique;
    }

    public function setBoutique(?Boutique $boutique): self
    {
        $this->boutique = $boutique;

        return $this;
    }

    /**
     * @return Collection<int, Note>
     */
    public function getNotes(): Collection
    {
        return $this->notes;
    }

    public function addNote(Note $note): self
    {
        if (!$this->notes->contains($note)) {
            $this->notes[] = $note;
            $note->setProduit($this);
        }

        return $this;
    }

    public function removeNote(Note $note): self
    {
        if ($this->notes->removeElement($note)) {
            // set the owning side to null (unless already changed)
            if ($note->getProduit() === $this) {
                $note->setProduit(null);
            }
        }

        return $this;
    }
}
