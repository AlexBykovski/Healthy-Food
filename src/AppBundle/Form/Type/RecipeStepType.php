<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RecipeStepType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('photo', TextType::class, [
                "label_attr" => ['class' => 'control-label'],
                "attr"       => ['class' => 'form-control input-inline'],
                "label" => 'Фото шага',
                "required" => false,
            ])
            ->add('description', TextAreaType::class, [
                "label_attr" => ['class' => 'control-label'],
                "attr"       => ['class' => 'form-control input-inline'],
                "label" => 'Описание шага'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\RecipeStep',
            'validation_groups' => array('recipe_step_create')
        ));
    }
}