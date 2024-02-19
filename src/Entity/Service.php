<?php

namespace App\Entity;

use App\Repository\ServiceRepository;
use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use PHPUnit\Util\Json as UtilJson;
use Symfony\Component\Validator\Constraints\Json;

#[ORM\Entity(repositoryClass: ServiceRepository::class)]
class Service
{


    // function __construct(int $id, array $services,DateTime $date_creation,DateTime $date_modification=null)
    // {
    //     $this->id = $id ;
    //     $this->services = $services ;
    //     $this->date_creation=$date_creation; 
    //     $this->date_modification=$date_modification;   

    // } 
    
    #[ORM\Id] 
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::JSON)]
    private  $services ; 

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date_creation = null; 

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date_modification = null;  

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getServices()
    {
        return $this->services;
    }

    public function setServices($services): static
    {
        $this->services = $services;    

        return $this;
    }

    public function setProperty($property,$value){

        $this->$property=$value;  
       return $this;
   }  
   

   public function setProperties( $enter){    
    
       foreach ($enter as $property => $value) {
           $setter = 'set' . ucfirst($property);  
           $this->$setter($value);           
       }      
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

   public function getDateModification(): ?\DateTimeInterface
   {
       return $this->date_modification;
   }

   public function setDateModification(\DateTimeInterface $date_modification): static
   {
       $this->date_modification = $date_modification;

       return $this;
   }
}
