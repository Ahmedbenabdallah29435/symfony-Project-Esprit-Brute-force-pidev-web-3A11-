<?php

namespace App\Entity;

use App\Repository\EvenementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

use Symfony\Component\Serializer\Annotation\Groups;


use Symfony\Component\Form\Extension\Core\Type\FileType;

/**
 * @ORM\Entity(repositoryClass=EvenementRepository::class)
 */
class Evenement
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=30)
     * @Assert\NotBlank(message="nom is required")
     * @Groups("evenement:read")

     */
    private $nom;


    /**
     * @ORM\Column(type="float", nullable=true)
     * @Assert\NotBlank(message="prix is required")
     * @Groups("evenement:read")

     */
    private $prix;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank(message="adresse is required")

     */
    private $adresse;

    /**
     
     
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank(message="date is required")
     * @var string A "Y-m-d H:i:s" formatted value
     * @Assert\GreaterThanOrEqual("today")
     * @Groups("evenement:read")

     */
    private $date;

    /**
 
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank(message="datefin is required")
     * @var string A "Y-m-d H:i:s" formatted value
     * @Assert\GreaterThanOrEqual(propertyPath="date", message="La date du fin doit être supérieure à la date début")

     */
    private $datefin;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\NotBlank(message="description is required")
     * @Groups("evenement:read")
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("evenement:read")
     */
    private $affiche;

    /**
     * @ORM\OneToMany(targetEntity=Commentaires::class, mappedBy="annonces", orphanRemoval=true)
     * @Groups("evenement:read")
     */
    private $commentaires;

    /**
     * @ORM\Column(type="text")
     */
    private $longdesc;

    /**
     * @ORM\OneToMany(targetEntity=Note::class, mappedBy="evenement")
     * @Groups("evenement:read")
     */
    private $notes;

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
        $this->commentaires = new ArrayCollection();
        $this->notes = new ArrayCollection();
        $this->date = new \DateTime('now');
        $this->datefin = new \DateTime('now');
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

   

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(?float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getDatefin(): ?\DateTimeInterface
    {
        return $this->datefin;
    }

    public function setDatefin(\DateTimeInterface $datefin): self
    {
        $this->datefin = $datefin;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getAffiche(): ?string
    {
        return $this->affiche;
    }

    public function setAffiche(string $affiche): self
    {
        $this->affiche = $affiche;

        return $this;
    }

    /**
     * @return Collection|Commentaires[]
     */
    public function getCommentaires(): Collection
    {
        return $this->commentaires;
    }

    public function addCommentaire(Commentaires $commentaire): self
    {
        if (!$this->commentaires->contains($commentaire)) {
            $this->commentaires[] = $commentaire;
            $commentaire->setAnnonces($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentaires $commentaire): self
    {
        if ($this->commentaires->removeElement($commentaire)) {
            // set the owning side to null (unless already changed)
            if ($commentaire->getAnnonces() === $this) {
                $commentaire->setAnnonces(null);
            }
        }

        return $this;
    }

    public function getLongdesc(): ?string
    {
        return $this->longdesc;
    }

    public function setLongdesc(string $longdesc): self
    {
        $this->longdesc = $longdesc;

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
            $note->setEvenement($this);
        }

        return $this;
    }

    public function removeNote(Note $note): self
    {
        if ($this->notes->removeElement($note)) {
            // set the owning side to null (unless already changed)
            if ($note->getEvenement() === $this) {
                $note->setEvenement(null);
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
