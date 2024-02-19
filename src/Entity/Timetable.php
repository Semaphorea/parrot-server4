<?php

namespace App\Entity;

use App\Repository\TimetableRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM; 

#[ORM\Entity(repositoryClass: TimetableRepository::class)]
class Timetable
{
    protected const TIMEZONE = "Europe/Paris";
  

    // function __construct(int $id, ?string  $day, ?\DateTimeInterface $date, array $timetable)
    // {
    //     $this->id = $id;
    //     $this->day = $day;
    //     $this->date = $date;
    //     $this->timetable = $timetable;
    // }

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 64, nullable: true)]
    private ?string $day = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(type: Types::ARRAY)]  
    private array $timetable = ["Debut_de_matinee"=>"","Fin_de_matinee"=>"","Debut_apres-midi"=>"","Fin_apres-midi"=>""];

    #[ORM\Column]
    private bool $active = false;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getDay(): ?string
    {
        return $this->day;
    }

    public function setDay(?string $day): static
    {
        $this->day = $day;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getTimetable(): array
    {
        return $this->timetable;
    }

    public function setTimetable(array $timetable): static
    {
        $this->timetable = $timetable;

        return $this;
    }

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;   

        return $this;
    }

    public function setProperty($property,$value){

        $this->$property=$value;  
       return $this;
   }  

   public function setProperties( $enter){    
    
       foreach ($enter as $property => $value) {
           $setter = 'set' . ucfirst($property);
  
           if ($property == "date"){   $value = \DateTime::createFromFormat('d/m/Y', $value, new  \DateTimeZone(Timetable::TIMEZONE));}
           $this->$setter($value);
       }

}
}