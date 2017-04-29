<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Eating;
use AppBundle\Entity\Recipe;
use AppBundle\Entity\RecipeProduct;
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

        $parseRecipes = [];

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
            ];

            /** @var RecipeProduct $product */
            foreach($recipe->getProducts() as $product){
                $parseRecipes[count($parseRecipes) - 1]['products'][] = $product->getName();
            }
        }

        return $this->render('recipe/list-recipes.html.twig', [
            "recipes" => $parseRecipes,
            "chosenEating" => $chosenEating,
            "canChoose" => (new DateTime())->format("Y-m-d") <= $date,
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
     * @Route("/choose-eating-recipe/{date}/{recipeId}/{type}", name="choose_eating_recipe")
     * @ParamConverter("recipe", class="AppBundle:Recipe", options={"id" = "recipeId"})
     * @Security("has_role('ROLE_SIMPLE_USER')")
     */
    public function chooseEatingRecipeAction(Request $request, Recipe $recipe, $date, $type)
    {
        if((new DateTime())->format("Y-m-d") > $date){
            return $this->redirect($request->headers->get('referer'));
        }

        $em = $this->getDoctrine()->getManager();
        $eating = new Eating();

        $eating->setRecipe($recipe);
        $eating->setUser($this->getUser());
        $eating->setDate(DateTime::createFromFormat('Y-m-d', $date));

        $chosenEating = $this->getDoctrine()->getRepository(Eating::class)
            ->findEatingForUserByDateAndType($this->getUser(), DateTime::createFromFormat('Y-m-d', $date), $this->getEatingNameByType($type));

        $em->persist($eating);

        if($chosenEating instanceof Eating) {
            $em->remove($chosenEating);
        }

        $em->flush();

        return $this->redirect($request->headers->get('referer'));
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