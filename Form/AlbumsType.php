<?php

namespace App\Form;

use App\Entity\Albums;
use App\Entity\Artiste;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class AlbumsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre')
            ->add('DateSorties', DateType::class, [
                'format' => 'dd-MM-yyyy',
                'widget' => 'choice',
                'years' => range(date('Y') + 15, date('Y') - 49),

            ])
            ->add('Artiste', EntityType::class, [
                'class' => Artiste::class,
                'choice_label' => 'nomArtiste',
                'multiple' => false,
                'expanded' => false,
                'placeholder' => '',
            ])

            ->add('deezerId')
            ->add('Idyoutube')
            ->add('imageAlbum', FileType::class, [
                'required' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Albums::class,
        ]);
    }
}
