<?php

namespace AppBundle\Helper;

use AppBundle\Entity\Eating;
use AppBundle\Entity\Recipe;
use AppBundle\Entity\RecipeProduct;
use Doctrine\ORM\EntityManagerInterface;

class RecipeHelper
{
    private $em;

    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
    }

    public function getPortionWeight($products){
        $weight = 0;

        /** @var RecipeProduct $product */
        foreach($products as $product){
            $weight += $product->getCount();
        }

        return $weight;
    }

    public function getParseEatings($chosenEating, $parseType, $mostPopularRecipeId){
        $parseRecipes = [];

        $allRecipes = $this->em->getRepository("AppBundle:Recipe")->findBy(["eatingType" => $parseType]);

        /** @var Recipe $recipe */
        foreach($allRecipes as $recipe){
            if($chosenEating instanceof Eating && $chosenEating->getRecipe()->getId() === $recipe->getId()){
                continue;
            }

            $parseRecipes[] = [
                'id' => $recipe->getId(),
                'photo' => $recipe->getPhoto(),
                'name' => $recipe->getName(),
                'products' => [],
                'portions' => $recipe->getPortions(),
                'portionWeight' => $this->getPortionWeight($recipe->getProducts()) / $recipe->getPortions(),
            ];

            /** @var RecipeProduct $product */
            foreach($recipe->getProducts() as $product){
                $parseRecipes[count($parseRecipes) - 1]['products'][] = $product->getName();
            }

            if($mostPopularRecipeId == $recipe->getId() && count($parseRecipes) > 1){ //set most popular on the first
                $temp = $parseRecipes[0];
                $parseRecipes[0] = $parseRecipes[count($parseRecipes) - 1];
                $parseRecipes[count($parseRecipes) - 1] = $temp;
            }
        }

        return $parseRecipes;
    }

    public function isAvailableRecipe($calories, $portions, $type){
        return true;
    }
}