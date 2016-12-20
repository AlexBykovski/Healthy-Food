<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class EatingController extends Controller
{
    /**
     * @Route("/eating-list", name="eating_list")
     */
    public function eatingListAction(Request $request)
    {
        /*if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }*/

        return $this->render(
            'eating/list.html.twig',
            []
        );
    }
}