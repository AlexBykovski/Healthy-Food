<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class RecipeController extends Controller
{
    /**
     * @Route("/list-recipes/{type}", name="list_recipes")
     */
    public function listDoctorsSpecificDirectionAction(Request $request, $type)
    {
        return $this->render('recipe/list-recipes.html.twig', [
            "recipes" => $this->getDoctrine()->getRepository("AppBundle:Recipe")->findBy(["eatingType" => $this->getEatingNameByType($type)])
        ]);
    }

    private function getEatingNameByType($type){
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