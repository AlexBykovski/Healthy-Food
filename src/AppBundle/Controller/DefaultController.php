<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Eating;
use AppBundle\Entity\Recipe;
use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        $user = $this->getUser();
        $userEatingData = [
            "proteins" => [],
            "carbohydrates" => [],
            "fats" => [],
            "calories" => [],
        ];

        if($user instanceof User){
            $eatings = $this->getDoctrine()->getRepository(Eating::class)->findDailyEatingForUser($user, new \DateTime());
            uasort($eatings, [$this, "sortEatingByType"]);
            /** @var Eating $eating */
            foreach($eatings as $eating){
                /** @var Recipe $recipe */
                $recipe = $eating->getRecipe();
                $userEatingData["proteins"][$recipe->getEatingType()] = $recipe->getProteins();
                $userEatingData["carbohydrates"][$recipe->getEatingType()] = $recipe->getCarbohydrates();
                $userEatingData["fats"][$recipe->getEatingType()] = $recipe->getFats();
                $userEatingData["calories"][$recipe->getEatingType()] = $recipe->getCalories();
            }
        }

        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
            'addr' => $request->server->get('REMOTE_ADDR'),
            'userEatingData' => $userEatingData,
        ]);
    }

    protected function sortEatingByType(Eating $a, Eating $b){
        $aType = $a->getRecipe()->getEatingType();
        $bType = $b->getRecipe()->getEatingType();

        if($aType === Recipe::BREAKFAST ||
            ($aType === Recipe::LUNCH && ($bType === Recipe::DINNER || $bType === Recipe::AFTERNOON_SNACK || $bType === Recipe::SUPPER || $bType === Recipe::SEC_SUPPER)) ||
            ($aType === Recipe::DINNER && ($bType === Recipe::AFTERNOON_SNACK || $bType === Recipe::SUPPER || $bType === Recipe::SEC_SUPPER)) ||
            ($aType === Recipe::AFTERNOON_SNACK && ($bType === Recipe::SUPPER || $bType === Recipe::SEC_SUPPER)) ||
            ($aType === Recipe::SUPPER && $bType === Recipe::SEC_SUPPER)){
            return -1;
        }

        return 1;
    }
}
