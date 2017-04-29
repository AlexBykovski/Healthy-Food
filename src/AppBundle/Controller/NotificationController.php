<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Notification;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class NotificationController extends Controller
{
    /**
     * @Route("/notifications", name="notifications")
     * @Security("has_role('ROLE_SIMPLE_USER')")
     */
    public function showListNotificationsAction(Request $request)
    {
        $notifications = $this->getDoctrine()->getRepository(Notification::class)
            ->findBy(["user" => $this->getUser()], ["createdAt" => "DESC"], 20);
        $firstEatingRemind = $this->getDoctrine()->getRepository(Notification::class)
            ->getFirstUnreadNotificationByUserAndType($this->getUser(), Notification::EATING_REMIND);
        $firstProfile = $this->getDoctrine()->getRepository(Notification::class)
            ->getFirstUnreadNotificationByUserAndType($this->getUser(), Notification::PROFILE_ACTION);

        /** @var Notification $eatingRemindNotif */
        foreach($notifications as $eatingRemindNotif){
            $eatingRemindNotif->setIsRead(true);
        }

        $this->getDoctrine()->getManager()->flush();

        return $this->render('notification/list.html.twig', [
            "notifications" => $notifications,
            "firstEatingRemind" => count($firstEatingRemind) ? $firstEatingRemind[0] : null,
            "firstProfile" => count($firstProfile) ? $firstProfile[0] : null,
        ]);
    }
}