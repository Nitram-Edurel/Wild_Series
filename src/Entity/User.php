<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $comments;

    /**
     * @ORM\OneToMany(targetEntity=Program::class, mappedBy="owner")
     */
    private $programs;

    /**
     * @ORM\ManyToOne(targetEntity=Program::class, inversedBy="users")
     */
    private $owner;

    /**
     * @ORM\OneToMany(targetEntity=Program::class, mappedBy="owner")
     */
    private $User;

    public function __construct()
    {
        $this->User = new ArrayCollection();
        $this->programs = new ArrayCollection();
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

    public function getRoles(): ?array
    {
        return $this->roles;
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getComments(): ?string
    {
        return $this->comments;
    }

    public function setComments(string $comments): self
    {
        $this->comments = $comments;

        return $this;
    }

    public function getPrograms(): ?string
    {
        return $this->programs;
    }

    public function setPrograms(string $programs): self
    {
        $this->programs = $programs;

        return $this;
    }

    public function getOwner(): ?Program
    {
        return $this->owner;
    }

    public function setOwner(?Program $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * @return Collection|Program[]
     */
    public function getUser(): Collection
    {
        return $this->User;
    }

    public function addUser(Program $user): self
    {
        if (!$this->User->contains($user)) {
            $this->User[] = $user;
            $user->setOwner($this);
        }

        return $this;
    }

    public function removeUser(Program $user): self
    {
        if ($this->User->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getOwner() === $this) {
                $user->setOwner(null);
            }
        }

        return $this;
    }

    public function addProgram(Program $program): self
    {
        if (!$this->program->contains($program)) {
            $this->programs[] = $program;
            $program->setOwner($this);
        }

        return $this;
    }

    public function removeProgram(Program $program): self
    {
        if ($this->program->removeElement($program)) {
            // set the owning side to null (unless already changed)
            if ($program->getOwner() === $this) {
                $program->setOwner(null);
            }
        }

        return $this;
    }
}
