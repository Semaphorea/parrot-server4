<?php

namespace App\Form;

use App\Entity\Photo;
use App\Entity\Vehicule;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver; 
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;   
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints\File;



class VehiculeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('brand')  
            ->add('model')
            ->add('features', TextareaType::class,[  'attr'=>['class' => 'control-form', 'placeholder'=>'[{element1:value1},{element2:value2}]']])             
            ->add('year', DateTimeType::class,[      'widget' => 'single_text',
                                                     'html5'=>false,  
                                                     'format' => 'yyyy',                                                     
                                                     'attr' => ['class' => 'control-form','placeholder'=>'yyyy']])
            ->add('kilometers')
            ->add('type')
            ->add('price')
            ->add('photo', FileType::class, [ 
                 'label' => 'Photo file',
                 'mapped' => false,
                 'attr' => ['class' => 'control-form, fieldphotovehicule'],   
                 'required' => false,
                 'constraints' => [  
                                    new File([      
                                        'maxSize' => '2048k',  
                                        'extensions'=> ['jpg','jpeg','png'], 
                                        'mimeTypesMessage' => 'Please upload a valid Photo file',
                                    ])  
                 ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Vehicule::class,
        ]);
    }
}
