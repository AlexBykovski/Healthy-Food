<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
            'addr' => $request->server->get('REMOTE_ADDR')
        ]);
    }

    /**
     * @Route("/test-geocoder", name="test_geocoder")
     */
    public function testIndexAction(Request $request)
    {
        $result = $this->container
            ->get('bazinga_geocoder.geocoder')
            ->using('google_maps')
            ->geocode($request->server->get('REMOTE_ADDR'));

        var_dump($result->first());die;

        // replace this example code with whatever you need
        return $this->render('default/test_geocoder.html.twig', [
            'addr' => $request->server->get('REMOTE_ADDR')
        ]);
    }
}
