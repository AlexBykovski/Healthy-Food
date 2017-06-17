<?php

namespace AppBundle\Helper;

use AppBundle\Entity\Recipe;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;

class AutoSampleHelper
{
    private $em;

    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
    }

    public function getAutoSampleDishes($availableCalories, $countEating = 3){
        $shuffledEatings = $this->getShuffledEatings($countEating);
        $eatings = $this->getParsedEatings($shuffledEatings);
        $samples = [];

        foreach($eatings as $eatingKey => $eating){ // $eatingKey - from 0 to 5
            $newSamples = [];

            foreach($eating as $calories => $id){
                if($eatingKey === 0 && $calories <= $availableCalories){ // breakfast - the first eating
                    $newSamples[$calories] = [$id];

                    continue;
                }

                foreach($samples as $sumCalories => $indexes){
                    $sum = $sumCalories + $calories;

                    if($sum <= $availableCalories){
                        $tempIndexes = array_merge($indexes, [$id]);
                        $newSamples[$sum] = $tempIndexes;
                    }
                }
            }

            $samples = $newSamples;
        }

        return $samples[max(array_keys($samples))];
    }

    public function getSampleRecipesIds($sample){
        $ids = [];

        foreach($sample as $item){
            $ids[] = $item["id"];
        }

        return $ids;
    }

    protected function getShuffledEatings($countEating){
        $shuffledEatings = [];

        $breakfasts = $this->em->getRepository(Recipe::class)->getEatingForUserByDateAndType(Recipe::BREAKFAST);
        shuffle($breakfasts);
        $shuffledEatings[] = $breakfasts;

        if($countEating > 3){
            $lunch = $this->em->getRepository(Recipe::class)->getEatingForUserByDateAndType(Recipe::LUNCH);
            shuffle($lunch);
            $shuffledEatings[] = $lunch;
        }

        $dinner = $this->em->getRepository(Recipe::class)->getEatingForUserByDateAndType(Recipe::DINNER);
        shuffle($dinner);
        $shuffledEatings[] = $dinner;

        if($countEating > 3){
            $afternoonSnack = $this->em->getRepository(Recipe::class)->getEatingForUserByDateAndType(Recipe::AFTERNOON_SNACK);
            shuffle($afternoonSnack);
            $shuffledEatings[] = $afternoonSnack;
        }

        $supper = $this->em->getRepository(Recipe::class)->getEatingForUserByDateAndType(Recipe::SUPPER);
        shuffle($supper);
        $shuffledEatings[] = $supper;

        if($countEating > 3) {
            $secondSupper = $this->em->getRepository(Recipe::class)->getEatingForUserByDateAndType(Recipe::SEC_SUPPER);
            shuffle($secondSupper);
            $shuffledEatings[] = $secondSupper;
        }

        return $shuffledEatings;
    }

    protected function getParsedEatings(array $eatings = []){ // max 4 portions for every dish
        $parsedEatings = [];

        /**
         * @var int $key
         * @var array $eating
         */
        foreach($eatings as $key => $eating){
            $parsedEatings[$key] = [];

            /** @var array $recipe */
            foreach($eating as $recipe){
                $caloriesOnePortion = intval($recipe["calories"] / $recipe["portions"]);

                $parsedEatings[$key][$caloriesOnePortion] = [
                    "id" => $recipe["id"],
                    "portions" => 1,
                ];
                $parsedEatings[$key][$caloriesOnePortion * 2] = [
                    "id" => $recipe["id"],
                    "portions" => 2,
                ];;
                $parsedEatings[$key][$caloriesOnePortion * 3] = [
                    "id" => $recipe["id"],
                    "portions" => 3,
                ];;
                $parsedEatings[$key][$caloriesOnePortion * 4] = [
                    "id" => $recipe["id"],
                    "portions" => 4,
                ];;
            }

        }

        return $parsedEatings;
    }
}