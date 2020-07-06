<?php

namespace App\Form;

use App\Entity\Project;
use App\Entity\Poster;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;

class ProjectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'required'=> true
            ])
            ->add('description', TextType::class, [
                'required'=> true
            ])
            ->add('dateStart', DateType::class, [
                 'required'=> true,
                 'widget' => 'single_text'
            ])
            ->add('dateEnd', DateType::class, [
                  'required'=> false,
                  'widget' => 'single_text'
            ])
            ->add('poster', EntityType::class, [
                'class' => Poster::class,
                'choice_label' => 'slug',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Project::class,
        ]);
    }
}
