<?php

namespace App\Form;

use App\Entity\Service;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface; 
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;


class ServiceType extends AbstractType
{

    private EntityManagerInterface $entitymanager;

    function __construct(EntityManagerInterface $entitymanager){    
        $this->entitymanager=$entitymanager;

    }
       
    
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {  

        $services =$this->entitymanager->getRepository(Service::class)->find(1);       
        //$services= new ServiceRepository()->findById(['id'=>1]);  
        $builder  
            ->add('services',TextareaType::class ,['attr'=>['class'=>'form-control' , 'contenteditable '=>'true'], 'data'=>' {
                "services": [
                    {
                        "carrosserie": [
                             "retouche",
                             "tôlerie",
                             "peinture"                              
                        ]
                    },
                    {
                        "entretien de véhicule": [                            
                                 "thermiques (Vidange, checkup)",
                                 "électriques",  
                                 "nettoyage (extérieur, intérieur)",                           
                                 "prise en charge contrôle technique"                           
                        ]
                    },
                    {
                        "mécanique":  ["moteur"]                           
                    },
                    {
                        "prêt de véhicule": ["véhicule de remplacement",
                          "véhicule anciens",
                          "véhicules haut de gamme"                          
                        ]
                    },
                    { 
                        "vente occasion": []
                    },
                    {
                        "assistance": ["intervention de dépanneuse sur autoroute"                        
                        ] 
                    },
                    { 
                        "assurance": []
                    }
                ]
            }'])  ;

            $date_creation=null;  

            if($services!=null){
                 $date_creation=$services->getDateCreation();  
                }
            else if($date_creation == null){$date_creation=new \DateTime('now');}            
        
        $builder ->add('date_creation', DateTimeType::class,['attr'=>['class'=>'form-control','style'=>'display:none;'],'data'=>$date_creation, 'label'=>false] )    
                 ->add('date_modification', DateTimeType::class,['attr'=>['class'=>'form-control','style'=>'display:none;'],'data'=>new \DateTime('now'),'label'=>false]) ;      
        
    } 

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Service::class,
        ]);
    }
}
