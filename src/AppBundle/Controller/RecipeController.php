<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Eating;
use AppBundle\Entity\Recipe;
use AppBundle\Entity\RecipeProduct;
use AppBundle\Entity\User;
use AppBundle\Helper\ANNHelper;
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
     * @var ANNHelper
     */
    private $annHelper = null;

    /**
     * @var User
     */
    private $user = null;

    /**
     * @Route("/list-recipes/{date}/{type}", name="list_recipes")
     * @Security("has_role('ROLE_SIMPLE_USER')")
     */
    public function listDoctorsSpecificDirectionAction(Request $request, $date, $type)
    {
        /** @var RecipeHelper $recipeHelper */
        $recipeHelper = $this->get("app.helper.recipe_helper");
        $parseType = $recipeHelper->getEatingNameByType($type);

        $chosenEating = $this->getDoctrine()->getRepository("AppBundle:Eating")
            ->findEatingForUserByDateAndType($this->getUser(), DateTime::createFromFormat('Y-m-d', $date), $parseType);

        $popularRecipeObject = $this->getDoctrine()->getRepository("AppBundle:Eating")->findMorePopularRecipeForUser($this->getUser(), $parseType);
        $mostPopularRecipeId = null;

        if(count($popularRecipeObject)){
            $mostPopularRecipeId = $popularRecipeObject[0]["id"];
        }

        $allRecipes = $this->getDoctrine()->getRepository("AppBundle:Recipe")->findBy(["eatingType" => $parseType]);

        if($this->getUser()->getAnnWeights()) {
            /** @var ANNHelper $ann */
            $this->annHelper = $this->get("app.helper.ann_helper");
            $this->user = $this->getUser();

            uasort($allRecipes, [$this, "sortRecipesByANN"]);
        }

        $parseRecipes = $recipeHelper->getParseEatings($chosenEating, $parseType, $mostPopularRecipeId, $allRecipes);

        $dateTime = DateTime::createFromFormat("Y-m-d", $date);
        $availableCalories = $recipeHelper->getAvailableCaloriesForEating($type, $this->getUser(), $dateTime);

        return $this->render('recipe/list-recipes.html.twig', [
            "recipes" => $parseRecipes,
            "chosenEating" => $chosenEating,
            "canChoose" => (new DateTime())->format("Y-m-d") <= $date, // prevent choose for past
            "mostPopularRecipeId" => $mostPopularRecipeId,
            "date" => $date,
            "type" => $type,
            "availableCalories" => $availableCalories,
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
        /** @var ANNHelper $ann */
        $ann = $this->get("app.helper.ann_helper");
        $dateTime = DateTime::createFromFormat("Y-m-d", $date);

        if((new DateTime())->format("Y-m-d") > $date){ // prevent choose for past
            return $this->redirect($request->headers->get('referer'));
        }

        $availableCalories = $recipeHelper->getAvailableCaloriesForEating($type, $this->getUser(), $dateTime);

        if($availableCalories < ($recipe->getCalories() * $portions)){
            return new JsonResponse(["status" => 'calories', "available" => $availableCalories], 200);
        }

        $em = $this->getDoctrine()->getManager();
        $eating = new Eating();

        $eating->setRecipe($recipe);
        $eating->setUser($this->getUser());
        $eating->setDate(DateTime::createFromFormat('Y-m-d', $date));
        $eating->setPortions(intval($portions));

        $chosenEating = $this->getDoctrine()->getRepository(Eating::class)
            ->findEatingForUserByDateAndType($this->getUser(), DateTime::createFromFormat('Y-m-d', $date), $recipeHelper->getEatingNameByType($type));

        $em->persist($eating);

        if($chosenEating instanceof Eating) {
            $em->remove($chosenEating);
        }

        $em->flush();

        $ann->learnNetwork($recipe, $this->getUser());

        return new JsonResponse(["status" => 'ok'], 200);
    }

    protected function sortRecipesByANN($a, $b){
        if($this->annHelper->getANNRecipeResult($this->user, $a) < $this->annHelper->getANNRecipeResult($this->user, $b)){
            return 1;
        }

        return -1;
    }
}