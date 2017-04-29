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

    public function getUsersForNotify(){
        $minCountEating = $this->getCountMinEatingByCurrentType();
        var_dump($minCountEating);

        return $this->em->getRepository(User::class)->findUserToRemindEating($minCountEating);
    }

    public function getEatingTypeByTime(){
        $time = (new \DateTime())->format("H:i");

        if($time > "5:00" && $time < "6:00"){
            return "завтрак";
        }
        elseif($time > "9:00" && $time < "10:00" ){
            return "второй завтрак";
        }
        elseif($time > "11:00" && $time < "12:00"){
            return "обед";
        }
        elseif($time > "15:00" && $time < "16:00"){
            return "полдник";
        }
        elseif($time > "17:00" && $time < "18:00"){
            return "ужин";
        }
        elseif($time > "19:00" && $time < "24:00"){
            return "второй ужин";
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