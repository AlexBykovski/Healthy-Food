<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserRegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, [
                "label_attr" => ['class' => 'control-label'],
                "attr"       => ['class' => 'form-control input-inline'],
                "label" => 'Имя'
            ])
            ->add('lastName', TextType::class, [
                "label_attr" => ['class' => 'control-label'],
                "attr"       => ['class' => 'form-control input-inline'],
                "label" => 'Фамилия'
            ])
            ->add('age', TextType::class, [
                "label_attr" => ['class' => 'control-label'],
                "attr"       => ['class' => 'form-control input-inline'],
                "label" => 'Возраст'
            ])
            ->add('email', EmailType::class, [
                "label_attr" => ['class' => 'control-label'],
                "attr"       => ['class' => 'form-control input-inline'],
                "label" => 'Email'
            ])
            ->add('height', NumberType::class, [
                "label_attr" => ['class' => 'control-label'],
                "attr"       => ['class' => 'form-control input-inline'],
                "label" => 'Рост',
                "scale" => 1,
                "invalid_message" => "Некорректный рост",
            ])
            ->add('weight', NumberType::class, [
                "label_attr" => ['class' => 'control-label'],
                "attr"       => ['class' => 'form-control input-inline'],
                "label" => 'Вес',
                "scale" => 1,
                "invalid_message" => "Некорректный вес",
            ])
            ->add('gender', CheckboxType::class, [
                "label_attr" => ['class' => 'control-label'],
                "attr"       => ['class' => 'form-control input-inline'],
                "label" => 'Пол',
                "required" => false
            ])
            ->add('password', RepeatedType::class, [
                'invalid_message' => 'Пароли должны совпадать',
                'type' => PasswordType::class,
                'options' => array(
                    'attr' => array('class' => 'form-control input-inline'),
                    'label_attr' => ['class' => 'control-label']
                ),
                'first_options'  => array('label' => 'Пароль'),
                'second_options' => array('label' => 'Подтверждение пароля'),
                'required' => true,
            ])
            ->add('dietAdditionalInformation', DietAdditionalInformationType::class)
            ->add('submit', SubmitType::class, [
                "attr"  => ['class' => 'btn btn-primary m-t-10'],
                "label" => "Зарегистрироваться"
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\User',
            'validation_groups' => array('registration')
        ));
    }
}