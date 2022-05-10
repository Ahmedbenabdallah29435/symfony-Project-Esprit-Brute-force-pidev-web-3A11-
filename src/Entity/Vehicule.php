<?php

namespace App\Entity;

use DateTimeInterface;
use App\Entity\Utilisateurs;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\VehiculeRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

/**
 * @ORM\Entity(repositoryClass=VehiculeRepository::class)
 */
class Vehicule
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

   

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="matricule is required")
     */
    private $matricule;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $couleur;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $typeVehicule;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $marque;

    /**
     * @ORM\Column(type="datetime")
     */
    private $DateEntretient;

    /**
     * @ORM\OneToMany(targetEntity=Livraison::class, mappedBy="vehicule")
     */
    private $livraisons;

    /**
     * @ORM\OneToOne(targetEntity=Utilisateurs::class, cascade={"persist"})
     */
    private $livreur;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $EtatVehicule;

  

  

    

    public function __construct()
    {
        $this->livreurId = new ArrayCollection();
        $this->livraisons = new ArrayCollection();
        $this->DateEntretient = new \DateTime('now');
    }

    public function getId(): ?int
    {
        return $this->id;
    }

   

    public function getMatricule(): ?string
    {
        return $this->matricule;
    }

    public function setMatricule(string $matricule): self
    {
        $this->matricule = $matricule;

        return $this;
    }

    public function getCouleur(): ?string
    {
        return $this->couleur;
    }

    public function setCouleur(string $couleur): self
    {
        $this->couleur = $couleur;

        return $this;
    }

    public function getTypeVehicule(): ?string
    {
        return $this->typeVehicule;
    }

    public function setTypeVehicule(string $typeVehicule): self
    {
        $this->typeVehicule = $typeVehicule;

        return $this;
    }

    public function getMarque(): ?string
    {
        return $this->marque;
    }

    public function setMarque(string $marque): self
    {
        $this->marque = $marque;

        return $this;
    }

    public function getDateEntretient(): ?\DateTimeInterface
    {
        return $this->DateEntretient;
    }

    public function setDateEntretient(\DateTimeInterface $DateEntretient): self
    {
        $this->DateEntretient = $DateEntretient;

        return $this;
    }
    public function getVehicule(): ?string
    {
        
        $vehicule = " typeVehicule: "  . $this->typeVehicule . " -- Marque :" . $this->marque . " -- Matricule: " .  $this->matricule;
        return $vehicule;
    }

    

    /**
     * @return Collection|Livraison[]
     */
    public function getLivraisons(): Collection
    {
        return $this->livraisons;
    }

    public function addLivraison(Livraison $livraison): self
    {
        if (!$this->livraisons->contains($livraison)) {
            $this->livraisons[] = $livraison;
            $livraison->setVehicule($this);
        }

        return $this;
    }

    public function removeLivraison(Livraison $livraison): self
    {
        if ($this->livraisons->removeElement($livraison)) {
            // set the owning side to null (unless already changed)
            if ($livraison->getVehicule() === $this) {
                $livraison->setVehicule(null);
            }
        }

        return $this;
    }

    public function getLivreur(): ?Utilisateurs
    {
        return $this->livreur;
    }

    public function setLivreur(?Utilisateurs $livreur): self
    {
        $this->livreur = $livreur;

        return $this;
    }

    public function getEtatVehicule(): ?string
    {
        return $this->EtatVehicule;
    }

    public function setEtatVehicule(string $EtatVehicule): self
    {
        $this->EtatVehicule = $EtatVehicule;

        return $this;
    }

    

    
    
}
