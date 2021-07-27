<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isVerified = false;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=CommentHotel::class, mappedBy="auteur")
     */
    private $commentHotels;

    /**
     * @ORM\OneToMany(targetEntity=CommentRestaurant::class, mappedBy="auteur")
     */
    private $commentRestaurants;

    public function __construct()
    {
        $this->commentHotels = new ArrayCollection();
        $this->commentRestaurants = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|CommentHotel[]
     */
    public function getCommentHotels(): Collection
    {
        return $this->commentHotels;
    }

    public function addCommentHotel(CommentHotel $commentHotel): self
    {
        if (!$this->commentHotels->contains($commentHotel)) {
            $this->commentHotels[] = $commentHotel;
            $commentHotel->setAuteur($this);
        }

        return $this;
    }

    public function removeCommentHotel(CommentHotel $commentHotel): self
    {
        if ($this->commentHotels->removeElement($commentHotel)) {
            // set the owning side to null (unless already changed)
            if ($commentHotel->getAuteur() === $this) {
                $commentHotel->setAuteur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|CommentRestaurant[]
     */
    public function getCommentRestaurants(): Collection
    {
        return $this->commentRestaurants;
    }

    public function addCommentRestaurant(CommentRestaurant $commentRestaurant): self
    {
        if (!$this->commentRestaurants->contains($commentRestaurant)) {
            $this->commentRestaurants[] = $commentRestaurant;
            $commentRestaurant->setAuteur($this);
        }

        return $this;
    }

    public function removeCommentRestaurant(CommentRestaurant $commentRestaurant): self
    {
        if ($this->commentRestaurants->removeElement($commentRestaurant)) {
            // set the owning side to null (unless already changed)
            if ($commentRestaurant->getAuteur() === $this) {
                $commentRestaurant->setAuteur(null);
            }
        }

        return $this;
    }
}
