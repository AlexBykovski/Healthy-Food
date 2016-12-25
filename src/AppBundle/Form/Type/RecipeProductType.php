<?php

namespace AppBundle\Form\Type;

use AppBundle\Entity\RecipeProduct;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RecipeProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $measureTypes = RecipeProduct::$measureTypes;

        $builder
            ->add('name', TextType::class, [
                "label_attr" => ['class' => 'control-label'],
                "attr"       => ['class' => 'form-control input-inline'],
                "label" => 'Название продукта'
            ])
            ->add('count', NumberType::class, [
                "label_attr" => ['class' => 'control-label'],
                "attr"       => ['class' => 'form-control input-inline'],
                "label" => 'Количество продукта'
            ])
            ->add('measure', ChoiceType::class, [
                "label_attr" => ['class' => 'control-label'],
                "attr"       => ['class' => 'form-control input-inline'],
                "label" => 'Единица измерения',
                'choices'  => array_combine( $measureTypes, $measureTypes ),
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\RecipeProduct',
            'validation_groups' => array('recipe_product_create')
        ));
    }
}