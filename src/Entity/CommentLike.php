<?php

namespace App\Entity;

use App\Repository\CommentLikeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CommentLikeRepository::class)
 */
class CommentLike
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Commentaires::class, inversedBy="likes")
     */
    private $comment;

    /**
     * @ORM\ManyToOne(targetEntity=Utilisateurs::class, inversedBy="likes")
     */
    private $utilisateur;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getComment(): ?Commentaires
    {
        return $this->comment;
    }

    public function setComment(?Commentaires $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function getUtilisateur(): ?Utilisateurs
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(?Utilisateurs $utilisateur): self
    {
        $this->utilisateur = $utilisateur;

        return $this;
    }
}
