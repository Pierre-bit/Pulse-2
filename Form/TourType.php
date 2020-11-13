<?php

namespace App\Form;

use App\Entity\Tour;
use App\Entity\Artiste;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class TourType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('Date', DateType::class, [
                'format' => 'dd-MM-yyyy',
                'widget' => 'choice',
                'years' => range(date('Y') + 15, date('Y') - 49),

            ])
            ->add('Artiste', EntityType::class, [
                'class' => Artiste::class,
                'choice_label' => 'nomArtiste',
                'multiple' => false,
                'expanded' => false,
            ])
            ->add('Adresse')
            ->add('Ville')
            ->add('Pays')
            ->add('Idyoutube')
            ->add('imageTour', FileType::class, [
                'required' => false
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Tour::class,
        ]);
    }
}
