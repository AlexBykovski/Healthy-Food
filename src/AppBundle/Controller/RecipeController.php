<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Eating;
use AppBundle\Entity\Recipe;
use AppBundle\Entity\RecipeProduct;
use AppBundle\Helper\RecipeHelper;
use \DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
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
        /** @var RecipeHelper $recipeHelper */
        $recipeHelper = $this->get("app.helper.recipe_helper");
        $parseType = $this->getEatingNameByType($type);

        $chosenEating = $this->getDoctrine()->getRepository("AppBundle:Eating")
            ->findEatingForUserByDateAndType($this->getUser(), DateTime::createFromFormat('Y-m-d', $date), $parseType);

        $popularRecipeObject = $this->getDoctrine()->getRepository("AppBundle:Eating")->findMorePopularRecipeForUser($this->getUser(), $parseType);
        $mostPopularRecipeId = null;

        if(count($popularRecipeObject)){
            $mostPopularRecipeId = $popularRecipeObject[0]["id"];
        }

        $parseRecipes = $recipeHelper->getParseEatings($chosenEating, $parseType, $mostPopularRecipeId);

        return $this->render('recipe/list-recipes.html.twig', [
            "recipes" => $parseRecipes,
            "chosenEating" => $chosenEating,
            "canChoose" => (new DateTime())->format("Y-m-d") <= $date, // prevent choose for past
            "mostPopularRecipeId" => $mostPopularRecipeId,
            "date" => $date,
            "type" => $type,
        ]);
    }

    //http://www.seantheme.com/color-admin-v2.2/frontend/e-commerce/product_detail.html
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

    //@@todo add requirements for date and recipeId
    /**
     * @Route("/choose-eating-recipe/{date}/{recipeId}/{type}/{portions}", name="choose_eating_recipe")
     * @ParamConverter("recipe", class="AppBundle:Recipe", options={"id" = "recipeId"})
     * @Security("has_role('ROLE_SIMPLE_USER')")
     */
    public function chooseEatingRecipeAction(Request $request, Recipe $recipe, $date, $type, $portions)
    {
        /** @var RecipeHelper $recipeHelper */
        $recipeHelper = $this->get("app.helper.recipe_helper");
        if((new DateTime())->format("Y-m-d") > $date){ // prevent choose for past
            return $this->redirect($request->headers->get('referer'));
        }

        if(!$recipeHelper->isAvailableRecipe($recipe->getCalories(), $portions, $type)){
            return new JsonResponse(["status" => 'unavailable'], 200);
        }

        $em = $this->getDoctrine()->getManager();
        $eating = new Eating();

        $eating->setRecipe($recipe);
        $eating->setUser($this->getUser());
        $eating->setDate(DateTime::createFromFormat('Y-m-d', $date));
        $eating->setPortions(intval($portions));

        $chosenEating = $this->getDoctrine()->getRepository(Eating::class)
            ->findEatingForUserByDateAndType($this->getUser(), DateTime::createFromFormat('Y-m-d', $date), $this->getEatingNameByType($type));

        $em->persist($eating);

        if($chosenEating instanceof Eating) {
            $em->remove($chosenEating);
        }

        $em->flush();

        return new JsonResponse(["status" => 'ok'], 200);
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