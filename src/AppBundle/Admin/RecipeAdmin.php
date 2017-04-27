<?php

namespace AppBundle\Admin;

use AppBundle\Entity\Recipe;
use AppBundle\Entity\RecipeStep;
use AppBundle\Form\Type\RecipeNutrientType;
use AppBundle\Form\Type\RecipeProductType;
use AppBundle\Form\Type\RecipeStepType;
use AppBundle\Helper\ImportDataByUrl;
use Doctrine\Common\Collections\ArrayCollection;
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
        $requester = $this->getConfigurationPool()->getContainer()->get("app.helper.import_data_by_url");
        $recipe = $requester->getRecipeDataByUrl();

        if($recipe){
            $formMapper
                ->add('name', 'text', [
                    'label' => 'Название',
                    'data' => $recipe->name,
                ])
                ->add('photo', 'text', [
                    'label' => 'Фото',
                    'data' => $recipe->photo,
                ])
                ->add('time', 'text', [
                    'label' => 'Время приготовления',
                ])
                ->add('portions', 'number', [
                    'label' => 'Количество порций',
                    'data' => $recipe->countPortions,
                ])
                ->add('eatingType', 'choice', [
                    'label' => 'Тип приёма пищи',
                    'choices' => array_combine( $eatingTypes, $eatingTypes ),
                    'data' => $eatingTypes[1],
                ])
                ->add('calories', 'number', [
                    'label' => 'Калорий',
                    'data' => $recipe->calories,
                ])
                ->add('proteins', 'number', [
                    'label' => 'Белков, r',
                    'data' => $recipe->proteins,
                ])
                ->add('fats', 'number', [
                    'label' => 'Жиров, r',
                    'data' => $recipe->fats,
                ])
                ->add('carbohydrates', 'number', [
                    'label' => 'Углеводов, r',
                    'data' => $recipe->carboh,
                ])
                ->add('steps', CollectionType::class, [
                    'entry_type'   => RecipeStepType::class,
                    'allow_add'    => true,
                    'attr'   => ["class" => "recipe-steps"],
                    'data' => $recipe->steps,
                ])
                ->add('products', CollectionType::class, [
                    'entry_type'   => RecipeProductType::class,
                    'allow_add'    => true,
                    'attr'   => ["class" => "recipe-products"],
                    'data' => $recipe->products,
                ]);
        }
        else {
            $formMapper
                ->add('name', 'text', [
                    'label' => 'Название',
                ])
                ->add('photo', 'text', [
                    'label' => 'Фото',
                ])
                ->add('time', 'text', [
                    'label' => 'Время приготовления',
                ])
                ->add('portions', 'number', [
                    'label' => 'Количество порций',
                ])
                ->add('eatingType', 'choice', [
                    'label' => 'Тип приёма пищи',
                    'choices' => array_combine($eatingTypes, $eatingTypes),
                ])
                ->add('calories', 'number', [
                    'label' => 'Калорий',
                ])
                ->add('proteins', 'number', [
                    'label' => 'Белков, r',
                ])
                ->add('fats', 'number', [
                    'label' => 'Жиров, r',
                ])
                ->add('carbohydrates', 'number', [
                    'label' => 'Углеводов, r',
                ])
                ->add('steps', CollectionType::class, [
                    'entry_type' => RecipeStepType::class,
                    'allow_add' => true,
                    'attr' => ["class" => "recipe-steps"],
                ])
                ->add('products', CollectionType::class, [
                    'entry_type' => RecipeProductType::class,
                    'allow_add' => true,
                    'attr' => ["class" => "recipe-products"]
                ]);
        }
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