<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Eating;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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

    /**
     * @Route("/eating-day/{date}", name="eating_day")
     * @Security("has_role('ROLE_SIMPLE_USER')")
     */
    public function eatingListOnDayAction(Request $request, $date)
    {
        $date = DateTime::createFromFormat("Y-m-d", $date);

        $chosenEatings = $this->getDoctrine()->getRepository(Eating::class)->findDailyEatingForUser($this->getUser(), $date);

        return $this->render(
            'eating/day-eating.html.twig',
            [
                "eatings" => $chosenEatings,
                "activeDate" => $date,
            ]
        );
    }
}