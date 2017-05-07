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
            ->add('countEating', ChoiceType::class, [
                "label_attr" => ['class' => 'control-label'],
                "attr"       => ['class' => 'form-control input-inline'],
                "label" => 'Количество приёмов пищи в день',
                "choices" => array_combine(range(3, 6), range(3, 6)),
            ])
            ->add('countTraining', ChoiceType::class, [
                "label_attr" => ['class' => 'control-label'],
                "attr"       => ['class' => 'form-control input-inline'],
                "label" => 'Количество тренировок в неделю',
                "choices" => array_combine(range(0, 21), range(0, 21)),
            ])
            ->add('trainingDifficulty', ChoiceType::class, [
                "label_attr" => ['class' => 'control-label'],
                "attr"       => ['class' => 'form-control input-inline'],
                "label" => 'Сложность тренировок (по шкале от 1 до 3)',
                "choices" => array_combine(range(1, 3), range(1, 3)),

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