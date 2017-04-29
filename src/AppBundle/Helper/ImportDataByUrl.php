<?php

namespace AppBundle\Helper;

use AppBundle\Entity\Recipe;
use AppBundle\Entity\RecipeProduct;
use AppBundle\Entity\RecipeStep;
use Doctrine\ORM\EntityManagerInterface;
use stdClass;
use Symfony\Component\HttpFoundation\RequestStack;

class ImportDataByUrl
{
    private $requestStack;
    private $em;

    public function __construct(RequestStack $requestStack, EntityManagerInterface $em) {
        $this->requestStack = $requestStack;
        $this->em = $em;
    }

    public function getRecipeDataByUrl(){
        $recipe = $this->requestStack->getCurrentRequest()->cookies->get("recipeRemote");
        $this->requestStack->getCurrentRequest()->cookies->remove("recipeRemote");

        if($this->isJson($recipe) && strpos($this->requestStack->getCurrentRequest()->getUri(), "/create")){
            /** @var stdClass $recipeObj */
            $recipeObj = json_decode($recipe);

            if(count($this->em->getRepository(Recipe::class)->findBy([
                "name" => $recipeObj->name,
                "portions" => $recipeObj->countPortions,
                "fats" => $recipeObj->fats,
                "proteins" => $recipeObj->proteins,
                "carbohydrates" => $recipeObj->carboh,
                "calories" => $recipeObj->calories,
            ]))){
                return null;
            }

            $steps = [];
            $products = [];

            foreach($recipeObj->steps as $step){
                $tempStep = new RecipeStep();
                $tempStep->setDescription($step);

                $steps[] = $tempStep;
            }

            $recipeObj->steps = $steps;

            foreach($recipeObj->products as $product){
                $tempProduct = new RecipeProduct();
                $tempProduct->setName(array_keys(get_object_vars($product))[0]);
                $tempProduct->setCount(array_values(get_object_vars($product))[0]);
                $tempProduct->setMeasure(isset($product->measure) ? $product->measure : 'гр');

                $products[] = $tempProduct;
            }

            $recipeObj->products = $products;

            return $recipeObj;
        }

        return null;
    }

    protected function isJson($string) {
        json_decode($string);
        var_dump(json_last_error_msg());
        return (json_last_error() == JSON_ERROR_NONE);
    }
}