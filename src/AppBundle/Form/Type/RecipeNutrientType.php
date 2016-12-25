<?php

namespace AppBundle\Form\Type;

use AppBundle\Entity\RecipeNutrient;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RecipeNutrientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $nutrientTypes = RecipeNutrient::$nutrientTypes;

        $builder
            ->add('name', ChoiceType::class, [
                "label_attr" => ['class' => 'control-label'],
                "attr"       => ['class' => 'form-control input-inline'],
                "label" => 'Название пит. эл.',
                'choices'  => array_combine( $nutrientTypes, $nutrientTypes),
            ])
            ->add('count', NumberType::class, [
                "label_attr" => ['class' => 'control-label'],
                "attr"       => ['class' => 'form-control input-inline'],
                "label" => 'Количество пит. эл.',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\RecipeNutrient',
            'validation_groups' => array('recipe_nutrient_create')
        ));
    }
}