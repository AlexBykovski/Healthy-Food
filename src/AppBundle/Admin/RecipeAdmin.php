<?php

namespace AppBundle\Admin;

use AppBundle\Entity\Recipe;
use AppBundle\Form\Type\RecipeNutrientType;
use AppBundle\Form\Type\RecipeProductType;
use AppBundle\Form\Type\RecipeStepType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Form\Type\CollectionType;
use Sonata\AdminBundle\Form\Type\Filter\ChoiceType;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;

class RecipeAdmin extends AbstractAdmin
{
// Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {
        $eatingTypes = Recipe::$eatingTypes;

        $formMapper
            ->add('name', 'text', array(
                'label' => 'Название'
            ))
            ->add('photo', 'text', array(
                'label' => 'Фото'
            ))
            ->add('time', 'text', array(
                'label' => 'Время приготовления'
            ))
            ->add('portions', 'number', array(
                'label' => 'Количество порций'
            ))
            ->add('eatingType', 'choice', array(
                'label' => 'Тип приёма пищи',
                'choices' => array_combine( $eatingTypes, $eatingTypes ),
            ))
            ->add('steps', CollectionType::class, array(
                'entry_type'   => RecipeStepType::class,
                'allow_add'    => true,
            ))
            ->add('products', CollectionType::class, array(
                'entry_type'   => RecipeProductType::class,
                'allow_add'    => true,
            ))
            ->add('nutrients', CollectionType::class, array(
                'entry_type'   => RecipeNutrientType::class,
                'allow_add'    => true,
            ))
        ;
    }

    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('name')
            ->add('eatingType')
        ;
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('name')
            ->add('eatingType')
            ->add('_action', 'actions', array(
                'actions' => array(
                    'edit' => [],
                    'show' => [],
                )
            ));
            //->add('author')
        ;
    }

    // Fields to be shown on show action
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->tab('General')
                ->add('name')
                ->add('time')
                ->add('portions')
                ->add('eatingType')
                ->add('photo')
                ->end()
            ->end()
            //->add('steps', 'collection')
            //->add('products')
            //->add('nutrients')
        ;
    }

    public function prePersist($recipe){
        foreach($recipe->getSteps() as $step){
            $step->setRecipe($recipe);
        }

        foreach($recipe->getProducts() as $product){
            $product->setRecipe($recipe);
        }

        foreach($recipe->getNutrients() as $nutrient){
            $nutrient->setRecipe($recipe);
        }
    }

    public function preUpdate($recipe){
        foreach($recipe->getSteps() as $step){
            $step->setRecipe($recipe);
        }

        foreach($recipe->getProducts() as $product){
            $product->setRecipe($recipe);
        }

        foreach($recipe->getNutrients() as $nutrient){
            $nutrient->setRecipe($recipe);
        }
    }
}