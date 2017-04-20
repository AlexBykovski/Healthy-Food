<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Recipe;
use \DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class RecipeController extends Controller
{
    /**
     * @Route("/list-recipes/{date}/{type}", name="list_recipes")
     * @Security("has_role('ROLE_SIMPLE_USER')")
     */
    public function listDoctorsSpecificDirectionAction(Request $request, $date, $type)
    {
        $allRecipes = $this->getDoctrine()->getRepository("AppBundle:Recipe")->findBy(["eatingType" => $this->getEatingNameByType($type)]);
        $chosenEating = $this->getDoctrine()->getRepository("AppBundle:Eating")
            ->findEatingForUserByDateAndType($this->getUser(), DateTime::createFromFormat('Y-m-d', $date), $this->getEatingNameByType($type));

        return $this->render('recipe/list-recipes.html.twig', [
            "recipes" => $allRecipes,
            "chosenEating" => $chosenEating,
        ]);
    }

    /**
     * @Route("/recipe/{id}", name="single_recipe")
     * @ParamConverter("recipe", class="AppBundle:Recipe", options={"id" = "id"})
     * @Security("has_role('ROLE_SIMPLE_USER')")
     */
    public function showSingleRecipeAction(Request $request, Recipe $recipe)
    {
        return $this->render('recipe/recipe-detail.html.twig', [
            "recipe" => $recipe,
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