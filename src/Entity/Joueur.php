<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups ;

/**
 * Joueur
 *
 * @ORM\Table(name="joueur", indexes={@ORM\Index(name="Categorie", columns={"Categorie"})})
 * @ORM\Entity
 */
class Joueur
{
    /**
     * @var int
     *
     * @ORM\Column(name="idJoueur", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     *@Groups("joueur")
     * @Groups("posts:read")
     */
    private $idjoueur;

    /**
     * @var string
     *
     * @ORM\Column(name="NomJoueur", type="string", length=255, nullable=false)
     * @Assert\NotBlank(message="Nom Joueur est Vide !")
     *@Groups("joueur")
     * @Groups("posts:read")
     */
    private $nomjoueur;

    /**
     * @var string
     *
     * @ORM\Column(name="PrenomJoueur", type="string", length=255, nullable=false)
     * @Assert\NotBlank(message="Prenom Joueur est Vide !")
     *@Groups("joueur")
     * @Groups("posts:read")
     */
    private $prenomjoueur;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="DateDeNaissance", type="date", nullable=false)
     *@Groups("joueur")
     * @Groups("posts:read")
     */
    private $datedenaissance;

    /**
     * @var int
     *
     * @ORM\Column(name="Age", type="integer", nullable=false)
     * @Assert\NotBlank(message="Age Joueur est Vide !")
     *@Groups("joueur")
     * @Groups("posts:read")
     */
    private $age;

    /**
     * @var string
     *
     * @ORM\Column(name="Sexe", type="string", length=255, nullable=false)
     *@Groups("joueur")
     * @Groups("posts:read")
     */
    private $sexe;

    /**
     * @var string
     *
     * @ORM\Column(name="Ville", type="string", length=255, nullable=false)
     * @Assert\NotBlank(message="Ville Joueur est Vide !")
     *@Groups("joueur")
     * @Groups("posts:read")
     */
    private $ville;

    /**
     * @var string
     *
     * @ORM\Column(name="imgJoueur", type="string", length=255, nullable=false)
     *@Groups("joueur")
     * @Groups("posts:read")
     */
    private $imgjoueur;

    /**
     * @var \Categorie
     *
     * @ORM\ManyToOne(targetEntity="Categorie")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="Categorie", referencedColumnName="idCategorie")
     * })
     *@Groups("joueur")
     * @Groups("posts:read")
     */
    private $categorie;

    public function getIdjoueur(): ?int
    {
        return $this->idjoueur;
    }

    public function getNomjoueur(): ?string
    {
        return $this->nomjoueur;
    }

    public function setNomjoueur(string $nomjoueur): self
    {
        $this->nomjoueur = $nomjoueur;

        return $this;
    }

    public function getPrenomjoueur(): ?string
    {
        return $this->prenomjoueur;
    }

    public function setPrenomjoueur(string $prenomjoueur): self
    {
        $this->prenomjoueur = $prenomjoueur;

        return $this;
    }

    public function getDatedenaissance(): ?\DateTimeInterface
    {
        return $this->datedenaissance;
    }

    public function setDatedenaissance(\DateTimeInterface $datedenaissance): self
    {
        $this->datedenaissance = $datedenaissance;

        return $this;
    }

    public function getAge(): ?int
    {
        return $this->age;
    }

    public function setAge(int $age): self
    {
        $this->age = $age;

        return $this;
    }

    public function getSexe(): ?string
    {
        return $this->sexe;
    }

    public function setSexe(string $sexe): self
    {
        $this->sexe = $sexe;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(string $ville): self
    {
        $this->ville = $ville;

        return $this;
    }

    public function getImgjoueur(): ?string
    {
        return $this->imgjoueur;
    }

    public function setImgjoueur(string $imgjoueur): self
    {
        $this->imgjoueur = $imgjoueur;

        return $this;
    }

    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }


}
