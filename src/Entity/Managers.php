<?php

namespace App\Entity;

use App\Repository\ManagersRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ManagersRepository::class)
 */
class Managers
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
    private $Seniority;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $Projects;

    /**
     * @ORM\OneToOne(targetEntity=User::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $User_id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $Email;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSeniority(): ?string
    {
        return $this->Seniority;
    }

    public function setSeniority(string $Seniority): self
    {
        $this->Seniority = $Seniority;

        return $this;
    }

    public function getProjects(): ?int
    {
        return $this->Projects;
    }

    public function setProjects(?int $Projects): self
    {
        $this->Projects = $Projects;

        return $this;
    }

    public function getUserId(): ?User
    {
        return $this->User_id;
    }

    public function setUserId(User $User_id): self
    {
        $this->User_id = $User_id;

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
}
