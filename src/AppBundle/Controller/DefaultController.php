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
}
