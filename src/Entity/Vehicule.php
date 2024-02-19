<?php

namespace App\Entity;

use App\Repository\VehiculeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Mixed_;
 
use Symfony\Component\Validator\Constraints\Json;

#[ORM\Entity(repositoryClass: VehiculeRepository::class)]
class Vehicule
{

  
   
    
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 64, nullable: true)]
    private ?string $brand= null;

    #[ORM\Column(length: 64, nullable: true)]
    private ?string $model = null;

    #[ORM\Column] 
    private ?array $features =[];         

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $year = null;

    #[ORM\Column(nullable: true)]
    private ?int $kilometers = null;

    #[ORM\Column(length: 64, nullable: true)]  
    private ?string $type = null;

    #[ORM\Column(length: 64, nullable: true)]
    private ?float $price = null;
    
    #[ORM\OneToOne(targetEntity:Photo::class,cascade: ['persist', 'remove'])] 
    #[ORM\JoinColumn(name:'photo_id',referencedColumnName:'id')] 
    private ?Photo $photo = null;  

    
   

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static 
    {
        $this->id = $id;

        return $this;
    }

    public function getBrand(): ?string
    {
        return $this->brand;
    }

    public function setBrand(?string $brand): static
    {
        $this->brand = $brand;

        return $this;
    }

    public function getModel(): ?string
    {
        return $this->model;
    }

    public function setModel(?string $model): static
    {
        $this->model = $model;

        return $this;
    }

    public function getFeatures(): ?array 
    {
        return $this->features;
    }



   public function setFeatures(Mixed $features)  
    {
      $feature =null;   
        var_dump($features);  
      $feature =  json_decode($features);
        if($features instanceof String){

        }
        if($feature!=null){
              $this->features = $feature;  
            }else{
            $this->features = $features;
        }

        return $this;
    }


    public function getYear(): ?\DateTimeInterface
    {
        return $this->year;
    }

    public function setYear(?\DateTimeInterface $year): static
    {
        $this->year = $year;

        return $this;
    }

    public function getKilometers(): ?int
    {
        return $this->kilometers;
    }

    public function setKilometers(?int $kilometers): static
    {
        $this->kilometers = $kilometers;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getPrice(): ?float  
    {
        return $this->price;
    }

    public function setPrice(?float $price): static
    {
        $this->price = $price;

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

    public function getPhoto(): ?Photo
    {
        return $this->photo;
    }

    public function setPhoto(?Photo $photo): static
    {
        $this->photo = $photo;

        return $this;  
    }    

}
