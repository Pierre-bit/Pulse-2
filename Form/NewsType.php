<?php

namespace App\Form;

use App\Entity\News;
use App\Entity\Tour;
use App\Entity\Albums;
use App\Entity\Single;
use App\Entity\Artiste;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class NewsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre')
            ->add('description', TextareaType::class, [ 
                'required' => false
            ])
            ->add('artiste', EntityType::class, [
                'class' => Artiste::class,
                'choice_label' => 'nomArtiste',
                'multiple' => false,
                'expanded' => false,
                'placeholder' => '',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => News::class,
        ]);
    }
}
