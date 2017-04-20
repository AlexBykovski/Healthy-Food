<?php

namespace AppBundle\Notifier;

use AppBundle\Builder\EmailBuilder;
use DateTime;

class RemindEating
{
    public function __construct(EmailBuilder $emailBuilder, EmailQueue $emailQueue){
        $this->emailBuilder = $emailBuilder;
        $this->emailQueue = $emailQueue;
    }

    public function sendEmail($email)
    {
        $message = $this->emailBuilder
            ->setSubject('Healthy Food')
            ->setTo($email)
            ->setTemplate('/email/remind-eating.html.twig')
            ->setTemplateVariables(["eatingType" => $this->getEatingTypeByTime()])
            ->build();

        $this->emailQueue->add($message);
    }

    protected function getEatingTypeByTime(){
        $time = (new DateTime())->format("H:i");

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
}
