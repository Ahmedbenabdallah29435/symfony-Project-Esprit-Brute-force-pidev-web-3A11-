<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\UtilisateursRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


/**
 * @ORM\Entity(repositoryClass=UtilisateursRepository::class)
 * @UniqueEntity(
 * fields={"Email"},
 * message= "Email already exist !" 
 * )
 */
class Utilisateurs implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     *@Assert\NotBlank(message="Last Name is required")
     */
    private $Nom;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="First Name is required")
     */
    private $Prenom;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Email is required")
     * @Assert\Email(message = "The email '{{ value }}' is not a valid email.")
     */
    private $Email;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank(message="Telephone is required")
     * @Assert\Length(min=8,
     *                max=8,
     *                minMessage = "Telephone must has {{ limit }} numbers",
     *                maxMessage = "Telephone must has {{ limit }} numbers")
     */
    private $Telephone;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Password is required")
     * @Assert\Length(min=8, minMessage = "Your Password must be at least {{ limit }} characters long",)
     */
    private $Password;

    /**
     * @Assert\EqualTo(propertyPath="Password", message="Password and confirm password doesn't match")
     */
    private $ConfirmPassword;

    

    /**
     * @ORM\Column(type="json")
     */
    private $Role = [];


    /**
     * @ORM\OneToOne(targetEntity=Boutique::class, orphanRemoval=true)
     */
    private $boutique;


    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Question 1 is required")
     */
    private $QuestionSecurite1;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Question 2 is required")
     */
    private $QuestionSecurite2;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Etat;
    /**
     * @ORM\OneToMany(targetEntity=Reclamation::class, mappedBy="client")
     */
    private $reclamations;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Image;

    /**
     * @ORM\OneToMany(targetEntity=CommentLike::class, mappedBy="utilisateur")
     */
    private $likes;

    protected $captchaCode;

    /**
     * @ORM\Column(type="string", length=255 , nullable=true)
     */
    private $Age;

    

    

    public function __construct()
    {
        $this->Livraison = new ArrayCollection();
        $this->reclamations = new ArrayCollection();
        $this->likes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->Nom;
    }

    public function setNom(string $Nom): self
    {
        $this->Nom = $Nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->Prenom;
    }

    public function setPrenom(string $Prenom): self
    {
        $this->Prenom = $Prenom;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->Email;
    }

    public function setEmail(string $Email): self
    {
        $this->Email = $Email;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->Telephone;
    }

    public function setTelephone(string $Telephone): self
    {
        $this->Telephone = $Telephone;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->Password;
    }

    public function setPassword(string $Password): self
    {
        $this->Password = $Password;

        return $this;
    }
    public function getConfirmPassword(): ?string
    {
        return $this->ConfirmPassword;
    }

    public function setConfirmPassword(string $ConfirmPassword): self
    {
        $this->ConfirmPassword = $ConfirmPassword;

        return $this;
    }
  
    public function getRole(): array
    {
        $roles = $this->Role;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRole(array $roles): self
    {
        $this->Role = $roles;

        return $this;
    }

   
    public function getRoles(): array
    {
        $roles = $this->Role;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }
    public function getUsername(): ?string
    {
        return $this->Email;
    }
    
    public function eraseCredentials(){}

    public function getSalt(){}

   
    public function getBoutique(): ?Boutique
    {
        return $this->boutique;
    }

    public function setBoutique(?Boutique $boutique): self
    {
        $this->boutique = $boutique;

        return $this;
    }

    public function getQuestionSecurite1(): ?string
    {
        return $this->QuestionSecurite1;
    }

    public function setQuestionSecurite1(string $QuestionSecurite1): self
    {
        $this->QuestionSecurite1 = $QuestionSecurite1;

        return $this;
    }

    public function getQuestionSecurite2(): ?string
    {
        return $this->QuestionSecurite2;
    }

    public function setQuestionSecurite2(string $QuestionSecurite2): self
    {
        $this->QuestionSecurite2 = $QuestionSecurite2;

        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->Etat;
    }

    public function setEtat(string $Etat): self
    {
        $this->Etat = $Etat;

        return $this;
    }
     /**
     * @return Collection|Reclamation[]
     */
    public function getReclamations(): Collection
    {
        return $this->reclamations;
    }

    public function addReclamation(Reclamation $reclamation): self
    {
        if (!$this->reclamations->contains($reclamation)) {
            $this->reclamations[] = $reclamation;
            $reclamation->setClient($this);
        }

        return $this;
    }

    public function removeReclamation(Reclamation $reclamation): self
    {
        if ($this->reclamations->removeElement($reclamation)) {
            // set the owning side to null (unless already changed)
            if ($reclamation->getClient() === $this) {
                $reclamation->setClient(null);
            }
        }

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->Image;
    }

    public function setImage(?string $Image): self
    {
        $this->Image = $Image;

        return $this;
    }

    /**
     * @return Collection<int, CommentLike>
     */
    public function getLikes(): Collection
    {
        return $this->likes;
    }

    public function addLike(CommentLike $like): self
    {
        if (!$this->likes->contains($like)) {
            $this->likes[] = $like;
            $like->setUtilisateur($this);
        }

        return $this;
    }

    public function removeLike(CommentLike $like): self
    {
        if ($this->likes->removeElement($like)) {
            // set the owning side to null (unless already changed)
            if ($like->getUtilisateur() === $this) {
                $like->setUtilisateur(null);
            }
        }

        return $this;
    }
    
    public function getCaptchaCode()
    {
        return $this->captchaCode;
    }

    public function setCaptchaCode($captchaCode)
    {
        $this->captchaCode = $captchaCode;
    }

    public function getAge(): ?string
    {
        return $this->Age;
    }

    public function setAge(string $Age): self
    {
        $this->Age = $Age;

        return $this;
    }

    
   
   
}
