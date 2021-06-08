<?php

namespace App\Entity;

use App\Repository\ConsultantsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ConsultantsRepository::class)
 */
class Consultants
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $Seniority;

    /**
     * @ORM\Column(type="integer")
     */
    private $Projects;

    /**
     * @ORM\OneToOne(targetEntity=User::class, inversedBy="consultants", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $User_id;

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

    public function setProjects(int $Projects): self
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
}
