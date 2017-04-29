<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class AIController extends Controller
{
    /**
     * @Route("/ai-supposition", name="ai_supposition")
     */
    public function suppositionAction(Request $request)
    {
        return $this->render('ai/supposition.html.twig', [
        ]);
    }

}