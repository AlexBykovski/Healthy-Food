<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DietAdditionalInformationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('countEating', IntegerType::class, [
                "label_attr" => ['class' => 'control-label'],
                "attr"       => ['class' => 'form-control input-inline'],
                "label" => 'Количество приёмов пищи в день'
            ])
            ->add('countTraining', IntegerType::class, [
                "label_attr" => ['class' => 'control-label'],
                "attr"       => ['class' => 'form-control input-inline'],
                "label" => 'Количество тренировок в неделю'
            ])
            ->add('trainingDifficulty', IntegerType::class, [
                "label_attr" => ['class' => 'control-label'],
                "attr"       => ['class' => 'form-control input-inline'],
                "label" => 'Сложность тренировок'
            ])
            ->add('purpose', ChoiceType::class, [
                "label_attr" => ['class' => 'control-label'],
                "attr"       => ['class' => 'form-control input-inline'],
                "label" => 'Цель',
                "choices"  => array(
                    'Сбросить вес' => 'Сбросить вес',
                    'Сохранить вес' => 'Сохранить вес',
                )
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\DietAdditionalInformation',
            'validation_groups' => array('registration')
        ));
    }
}