<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups ;

/**
 * Categorie
 *
 * @ORM\Table(name="categorie")
 * @ORM\Entity
 */
class Categorie
{
    /**
     * @var int
     *
     * @ORM\Column(name="idCategorie", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     *@Groups("categorie")
     * @Groups("posts:read")
     */
    private $idcategorie;

    /**
     * @var string
     *
     * @ORM\Column(name="NomCategorie", type="string", length=255, nullable=false)
     * @Assert\NotBlank(message="nom categorie est Vide !")
     * @Assert\Length(
     *      min = 2,
     *      max = 50,
     *      minMessage = "must be at least {{ limit }} characters long",
     *      maxMessage = "cannot be longer than {{ limit }} characters"
     * )
     *@Groups("categorie")
     * @Groups("posts:read")
     */
    private $nomcategorie;

    /**
     * @var string
     *
     * @ORM\Column(name="Genre", type="string", length=255, nullable=false)
     * @Assert\NotBlank(message="Genre est Vide !")
     * @Assert\Length(
     *      min = 2,
     *      max = 50,
     *      minMessage = "must be at least {{ limit }} characters long",
     *      maxMessage = "cannot be longer than {{ limit }} characters"
     * )
     *@Groups("categorie")
     * @Groups("posts:read")
     */
    private $genre;

    public function getIdcategorie(): ?int
    {
        return $this->idcategorie;
    }

    public function getNomcategorie(): ?string
    {
        return $this->nomcategorie;
    }

    public function setNomcategorie(string $nomcategorie): self
    {
        $this->nomcategorie = $nomcategorie;

        return $this;
    }

    public function getGenre(): ?string
    {
        return $this->genre;
    }

    public function setGenre(string $genre): self
    {
        $this->genre = $genre;

        return $this;
    }


}
