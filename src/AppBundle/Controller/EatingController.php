<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Eating;
use AppBundle\Entity\Recipe;
use AppBundle\Entity\User;
use AppBundle\Form\Type\AutoSampleType;
use AppBundle\Helper\ANNHelper;
use AppBundle\Helper\AutoSampleHelper;
use AppBundle\Helper\RecipeHelper;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;

class EatingController extends Controller
{
    /**
     * @Route("/eating-list/{date}", name="eating_list")
     * @Security("has_role('ROLE_SIMPLE_USER')")
     */
    public function eatingListAction(Request $request, $date)
    {
        /*if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }*/

        $date = DateTime::createFromFormat("d-m-Y", $date);

        if($date->format("d-m-Y") < (new DateTime())->format("d-m-Y")){
            $date->setTime(23, 59, 59);
        }
        elseif($date->format("d-m-Y") > (new DateTime())->format("d-m-Y")){
            $date->setTime(0, 0, 0);
        }

        return $this->render(
            'eating/list.html.twig',
            [
                "user" => $this->getUser(),
                "activeDate" => $date,
            ]
        );
    }

    /**
     * @Route("/eating-day/{date}", name="eating_day")
     * @Security("has_role('ROLE_SIMPLE_USER')")
     */
    public function eatingListOnDayAction(Request $request, $date)
    {
        $date = DateTime::createFromFormat("Y-m-d", $date);

        $chosenEatings = $this->getDoctrine()->getRepository(Eating::class)->findDailyEatingForUser($this->getUser(), $date);
        uasort($chosenEatings, [$this, "sortEatingByType"]);

        return $this->render(
            'eating/day-eating.html.twig',
            [
                "eatings" => $chosenEatings,
                "activeDate" => $date,
            ]
        );
    }

    /**
     * @Route("/create-auto-sample/{date}", name="auto_sample_per_date")
     * @Security("has_role('ROLE_SIMPLE_USER')")
     */
    public function createAutoSampleDishes(Request $request, $date){
        /** @var User $user */
        $user = $this->getUser();
        /** @var RecipeHelper $recipeHelper */
        $recipeHelper = $this->get("app.helper.recipe_helper");
        /** @var AutoSampleHelper $autoSampleHelper */
        $autoSampleHelper = $this->get("app.helper.auto_sample_helper");

        $availableCalories = $recipeHelper->getCountAvailableCalories($user);

        $recipesWithPortions = $autoSampleHelper->getAutoSampleDishes($availableCalories, $user->getDietAdditionalInformation()->getCountEating());
        $request->cookies->set("auto_sample", "asd");

        $recipes = $this->getDoctrine()->getRepository(Recipe::class)->getRecipesByIds($autoSampleHelper->getSampleRecipesIds($recipesWithPortions));
        uasort($recipes, [$this, "sortEatingByType"]);
        $recipePortionsAssociation = $autoSampleHelper->getAssociationIdAndPortions($recipesWithPortions);

        /** @var Response $response */
        $response = $this->render(
            'eating/auto-sample.html.twig',
            [
                "date" => $date,
                "recipes" => $recipes,
                "recipePortions" => $recipePortionsAssociation,
            ]
        );

        $response->headers->setCookie(new Cookie('auto_sample', json_encode($recipePortionsAssociation)));

        return $response;
    }

    /**
     * @Route("/approve-auto-sample/{date}", name="auto_sample_approve")
     * @Security("has_role('ROLE_SIMPLE_USER')")
     */
    public function approveAutoSampleDishes(Request $request, $date){
        $sample = json_decode($request->cookies->get("auto_sample"), true);
        $em = $this->getDoctrine()->getManager();

        foreach($sample as $id => $portions){
            $eating = new Eating();
            /** @var Recipe $recipe */
            $recipe = $em->getRepository(Recipe::class)->find($id);

            $eating->setRecipe($recipe);
            $eating->setUser($this->getUser());
            $eating->setDate(DateTime::createFromFormat('d-m-Y', $date));
            $eating->setPortions(intval($portions));

            $chosenEating = $this->getDoctrine()->getRepository(Eating::class)
                ->findEatingForUserByDateAndType($this->getUser(), DateTime::createFromFormat('d-m-Y', $date), $recipe->getEatingType());

            if($chosenEating instanceof Eating) {
                $em->remove($chosenEating);
            }

            $em->persist($eating);

            /** @var ANNHelper $ann */
            $ann = $this->get("app.helper.ann_helper");

            $ann->learnNetwork($recipe, $this->getUser());
        }

        $em->flush();

        return $this->redirectToRoute("eating_day", ["date" => DateTime::createFromFormat('d-m-Y', $date)->format("Y-m-d")]);
    }

    protected function sortEatingByType($a, $b){
        $aType = null;
        $bType = null;

        if($a instanceof Eating && $b instanceof Eating){
            $aType = $a->getRecipe()->getEatingType();
            $bType = $b->getRecipe()->getEatingType();
        }
        if($a instanceof Recipe && $b instanceof Recipe){
            $aType = $a->getEatingType();
            $bType = $b->getEatingType();
        }

        if(!$aType || !$bType){
            return -1;
        }

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