<?php

namespace AppBundle\Helper;

use AppBundle\Entity\RecipeProduct;
use AppBundle\Entity\RecipeStep;
use Symfony\Component\HttpFoundation\RequestStack;

class ImportDataByUrl
{
    private $requestStack;

    public function __construct(RequestStack $requestStack) {
        $this->requestStack = $requestStack;
    }

    public function getRecipeDataByUrl(){
        $recipe = $this->requestStack->getCurrentRequest()->cookies->get("recipeRemote");
        $this->requestStack->getCurrentRequest()->cookies->remove("recipeRemote");

        if($this->isJson($recipe)){
            $recipeObj = json_decode($recipe);
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
                $tempProduct->setName($product->name);
                $tempProduct->setCount($product->count);
                $tempProduct->setMeasure($product->measure);

                $products[] = $tempProduct;
            }

            $recipeObj->products = $products;

            return $recipeObj;
        }

        return null;
    }

    protected function isJson($string) {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }
}