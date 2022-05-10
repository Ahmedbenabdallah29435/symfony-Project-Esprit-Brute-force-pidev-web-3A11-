<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\LigneCommandeRepository;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=LigneCommandeRepository::class)
 */
class LigneCommande
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("commande:read")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     * @Groups("commande:read")
     */
    private $quantite;

    /**
     * @ORM\ManyToOne(targetEntity=Produit::class, inversedBy="ligneCommandes")
     * @Groups("commande:read")
     */
    private $produit;

    /**
     * @ORM\ManyToOne(targetEntity=Commande::class, inversedBy="ligneCommandes" )
     */
    private $commande;

   

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): self
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function getProduit(): ?Produit
    {
        return $this->produit;
    }

    public function setProduit(?Produit $produit): self
    {
        $this->produit = $produit;

        return $this;
    }

    public function getCommande(): ?Commande
    {
        return $this->commande;
    }

    public function setCommande(?Commande $commande): self
    {
        $this->commande = $commande;

        return $this;
    }

   
}