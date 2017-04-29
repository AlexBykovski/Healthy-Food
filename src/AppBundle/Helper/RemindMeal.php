<?php

namespace AppBundle\Helper;

use AppBundle\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class RemindMeal
{
    private $em;

    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
    }

    public function getUsersEmailsForNotify(){
        $minCountEating = $this->getCountMinEatingByCurrentType();
        $emails = [];

        $users = $this->em->getRepository(User::class)->findUserToRemindEating($minCountEating);

        foreach($users as $user){
            $emails[] = $user["email"];
        }

        return $emails;
    }

    public function getEatingTypeByTime(){
        $time = (new \DateTime())->format("H:i");

        if($time > "05:00" && $time < "06:00"){
            return "завтрак"; //7
        }
        elseif($time > "09:00" && $time < "10:00" ){
            return "второй завтрак"; //6
        }
        elseif($time > "11:00" && $time < "12:00"){
            return "обед"; //7
        }
        elseif($time > "15:00" && $time < "16:00"){
            return "полдник"; //3
        }
        elseif($time > "17:00" && $time < "18:00"){
            return "ужин"; //7
        }
        elseif($time > "19:00" && $time < "24:00"){
            return "второй ужин"; //2
        }

        return "";
    }

    protected function getCountMinEatingByCurrentType(){
        switch($this->getEatingTypeByTime()){
            case "завтрак":
            case "обед":
            case "ужин":
                return 3;
            case "второй завтрак":
                return 4;
            case "полдник":
                return 5;
            case "второй ужин":
                return 6;
            default:
                return 6;
        }
    }
}