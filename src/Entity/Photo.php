<?php

namespace App\Entity;

use App\Repository\PhotoRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;




#[ORM\Entity(repositoryClass: PhotoRepository::class)]
class Photo
{





    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 64)]
    private ?string $title = null;

    #[ORM\Column(length: 256, nullable: true)]
    private ?string $url = null;

    #[ORM\Column(type: Types::BLOB)]
    private $photo = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date_creation = null;

 
    public function __construct(...$args)
    {

        $n = 0;
        $n = count($args);
        switch ($n) {
            case 0:
                $this->id = null;
                $this->title = null;
                $this->photo = null;
                break;

                //        function __construct(int $id, string $title, $photo )
            case 3:
                $this->id = $args[0];
                $this->title = $args[1];
                $this->photo = $args[2];
                break;

                //        function __construct(int $id, string $title, string $url, $photo, \DateTimeInterface $date_creation)
            case 5:
                $this->id = $args[0];
                $this->title = $title = $args[1];
                $this->url = $args[2];
                $this->photo = $args[3];
                $this->date_creation = $args[4];
                break;
        }
    }




    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): static
    {
        $this->url = $url;

        return $this;
    }

    public function getPhoto()
    {
        return $this->photo;
    }

    public function setPhoto($photo): static
    {
        $this->photo = $photo;

        return $this;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->date_creation;
    }

    public function setDateCreation(\DateTimeInterface $date_creation): static
    {
        $this->date_creation = $date_creation;

        return $this;
    }
}
