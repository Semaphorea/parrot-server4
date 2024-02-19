<?php

namespace App\Form;

use App\Entity\Timetable;
use PHPUnit\TextUI\XmlConfiguration\CodeCoverage\Report\Text;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class TimetableType extends AbstractType  
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder 
            ->add('day', TextType::class ,['attr'=>['placeholder'=>'lundi','class'=>'form-control'],'required' => false,])
            ->add('date', DateTimeType::class,['attr'=>['class'=>'form-control'],'required' => false,])  
            ->add('timetable',CollectionType::class, [            
                'entry_type' => TextType::class,
                 'entry_options' => [ 
                    'attr' => ['class' => 'email-box'],
                 ]])
            ->add('active', ChoiceType::class,['choices'=>['true'=>'1','false'=>'0'],'attr'=>['expanded'=>false,'multiple'=>false]])                
        ;
    }  

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Timetable::class,
        ]);
    }
}
