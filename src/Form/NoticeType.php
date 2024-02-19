<?php

namespace App\Form;

use App\Entity\Notice;
use App\Entity\Visitor; 
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NoticeType extends AbstractType
{
    // Le formulaire de création d'Avis n'est pas forcément nécessaire ici puisque l'adminstrateur n'a pas a créer de messages d'avis
    // Il fait de la modération 
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('message')
            ->add('datecreation', DateTimeType::class,['attr'=>[],'label'=>'Date Creation'])    
            // L'Id visiteur est a définir au moment ou la personne envoie le message et laisse ses coordonnées

            //-> Il faut pouvoir créer un nouveau visiteur
            ->add('id_visitor', EntityType::class, [
                'class' => Visitor::class,
                      'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Notice::class,
        ]);
    }
}
