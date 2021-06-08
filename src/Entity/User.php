<?php

namespace App\Entity;

use App\Repository\UserRepository;
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
     * @ORM\Column(type="string", length=50)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $Email;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $Password;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $Position;

    /**
     * @ORM\OneToOne(targetEntity=Consultants::class, mappedBy="User_id", cascade={"persist", "remove"})
     */
    private $consultants;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

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

    public function getPassword(): ?string
    {
        return $this->Password;
    }

    public function setPassword(string $Password): self
    {
        $this->Password = $Password;

        return $this;
    }

    public function getPosition(): ?string
    {
        return $this->Position;
    }

    public function setPosition(string $Position): self
    {
        $this->Position = $Position;

        return $this;
    }

    public function getConsultants(): ?Consultants
    {
        return $this->consultants;
    }

    public function setConsultants(Consultants $consultants): self
    {
        // set the owning side of the relation if necessary
        if ($consultants->getUserId() !== $this) {
            $consultants->setUserId($this);
        }

        $this->consultants = $consultants;

        return $this;
    }
}
