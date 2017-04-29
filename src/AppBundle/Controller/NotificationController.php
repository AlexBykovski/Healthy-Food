<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class NotificationController extends Controller
{
    /**
     * @Route("/notifications", name="notifications")
     */
    public function showListNotificationsAction(Request $request)
    {
        return $this->render('notification/list.html.twig', [
        ]);
    }
}