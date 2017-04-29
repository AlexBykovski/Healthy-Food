<?php

namespace AppBundle\Helper;

use AppBundle\Entity\Eating;
use AppBundle\Entity\Notification;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class NotificationHelper
{
    private $em;

    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
    }

    public function addEatingRemindNotification($email, $type){
        if(!!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $user = $this->em->getRepository(User::class)->findOneBy(["email" => $email]);
            if(!($user instanceof User)){
                return false;
            }


            $notification = new Notification();
            $notification->setMessage("Через полчаса (в " . $this->getStartTimeByEatingType($type) .
                ":00 ) у Вас запланирован приём пищи: " . mb_convert_case($type, MB_CASE_TITLE, "UTF-8"));
            $notification->setType(Notification::EATING_REMIND);
            $notification->setCreatedAt(new \DateTime());
            $notification->setIsRead(false);
            $notification->setUser($user);

            $this->em->persist($notification);
            $this->em->flush();
        }
    }

    public function getCountUnreadAllNotificationsForUser(User $user){
        return count($this->em->getRepository(Notification::class)->getUnreadNotificationsByUser($user, 'all'));
    }

    protected function getStartTimeByEatingType($type){
        switch($type){
            case "завтрак":
                return Eating::BREAKFAST_START;
            case "обед":
                return Eating::DINNER_START;
            case "ужин":
                return Eating::SUPPER_START;
            case "второй завтрак":
                return Eating::LUNCH_START;
            case "полдник":
                return Eating::AFTERNOON_SNACK_START;
            case "второй ужин":
                return Eating::SEC_SUPPER_START;
            default:
                return Eating::SEC_SUPPER_START;
        }
    }
}