<?php

namespace AppBundle\Helper;

use AppBundle\Entity\DietAdditionalInformation;
use AppBundle\Entity\Eating;
use AppBundle\Entity\Recipe;
use AppBundle\Entity\RecipeProduct;
use AppBundle\Entity\User;
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
                'calories' => $recipe->getCalories(),
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

    public function getAvailableCaloriesForEating($type, User $user, \DateTime $date){
        $availableCalories = $this->getCountAvailableCalories($user);

        $types = Recipe::$eatingTypes;
        $indexType = array_search($this->getEatingNameByType($type), $types);
        unset($types[$indexType]);

        $chosenEatings = $this->em->getRepository(Eating::class)->findDailyEatingForUser($user, $date);

        /** @var Eating $eating */
        foreach($chosenEatings as $eating){
            /** @var Recipe $recipe */
            $recipe = $eating->getRecipe();
            $indexType = array_search($recipe->getEatingType(), $types);
            unset($types[$indexType]);

            $availableCalories -= $recipe->getCalories() * $eating->getPortions();
        }

        return $availableCalories - $this->getMinimumRequiredCaloriesForTypes($types) + 100;
    }

    public function getCountAvailableCalories(User $user){ // formula of Mifflin St. Jeor
        $bmr = 10 * $user->getWeight() + 6.25 * $user->getHeight() - 5 * $user->getAge();

        if($user->isGenderMan()){
            $bmr += 5;
        }
        else{
            $bmr -= 161;
        }

        /** @var DietAdditionalInformation $dietInformation */
        $dietInformation = $user->getDietAdditionalInformation();

        return $this->getCaloriesByBMR($bmr, $dietInformation->getCountTraining(), $dietInformation->getTrainingDifficulty());
    }

    public function getCaloriesByBMR($bmr, $countTrainings, $difficulty){
        $coefficient = 1.2;
        $trainingCoefficient = $countTrainings * $difficulty;

        if( $trainingCoefficient > 3 && $trainingCoefficient < 10 ){
            $coefficient = 1.375;
        }
        else if($trainingCoefficient >= 10 && $trainingCoefficient < 20){
            $coefficient = 1.55;
        }
        else if($trainingCoefficient >= 20 && $trainingCoefficient < 42){
            $coefficient = 1.725;
        }
        else if($trainingCoefficient >= 42){
            $coefficient = 1.9;
        }

        return $bmr * $coefficient;
    }

    protected function getMinimumRequiredCaloriesForTypes($types){
        $reqiuredCalories = 0;

        foreach($types as $type){
            /** @var Recipe $recipe */
            $recipe = $this->em->getRepository(Recipe::class)->findBy(["eatingType" => $type], ["calories" => "ASC"], 1)[0];
            $reqiuredCalories += $recipe->getCalories();
        }

        return $reqiuredCalories;
    }

    public function getEatingNameByType($type){
        switch($type){
            case "breakfast":
                return "завтрак";
            case "sec-breakfast":
                return "второй завтрак";
            case "dinner":
                return "обед";
            case "afternoon-snack":
                return "полдник";
            case "supper":
                return "ужин";
            case "sec-supper":
                return "второй ужин";
        }
    }
}