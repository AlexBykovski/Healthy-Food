<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Eating;
use AppBundle\Entity\Recipe;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

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

    //@@todo add requirements for date and recipeId
    /**
     * @Route("/choose-eating-recipe/{date}/{recipeId}", name="choose_eating_recipe")
     * @ParamConverter("recipe", class="AppBundle:Recipe", options={"id" = "recipeId"})
     * @Security("has_role('ROLE_SIMPLE_USER')")
     */
    public function chooseEatingRecipeAction(Request $request, Recipe $recipe, $date)
    {
        $em = $this->getDoctrine()->getManager();
        $eating = new Eating();

        $eating->setRecipe($recipe);
        $eating->setUser($this->getUser());
        $eating->setDate(DateTime::createFromFormat('Y-m-d', $date));

        $em->persist($eating);
        $em->flush();

        return $this->redirect($request->headers->get('referer'));
    }
}