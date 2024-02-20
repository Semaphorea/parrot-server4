<?php

namespace App\Entity;

use App\Repository\EmployeeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EmployeeRepository::class)]
class Employee
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 64)]
    private ?string $lastname = null;  

    #[ORM\Column(length: 64)]
    private ?string $firstname = null;

    #[ORM\Column(length: 8, nullable: true)]
    private ?string $genre = null;

    #[ORM\Column(length: 128)]
    private ?string $email = null;

 

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): static
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getGenre(): ?string
    {
        return $this->genre;
    }

    public function setGenre(?string $genre): static
    {
        $this->genre = $genre;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }


    public function getAuhEntity(): ?AuthEntity
    {
        return $this->auhEntity;
    }

    public function setAuhEntity(?AuthEntity $auhEntity): static
    {
        // unset the owning side of the relation if necessary
        if ($auhEntity === null && $this->auhEntity !== null) {
            $this->auhEntity->setEmployee(null);
        } 

        // set the owning side of the relation if necessary
        if ($auhEntity !== null && $auhEntity->getEmployee() !== $this) {
            $auhEntity->setEmployee($this);
        }

        $this->auhEntity = $auhEntity;

        return $this;
    }
}
