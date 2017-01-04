<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class AboutUsController extends Controller
{
    /**
     * @Route("/about-author", name="about_us")
     */
    public function indexAction(Request $request)
    {
        return $this->render('about-us/about-us.html.twig', [
        ]);
    }
}