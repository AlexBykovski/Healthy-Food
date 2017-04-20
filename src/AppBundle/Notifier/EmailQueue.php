<?php

namespace AppBundle\Notifier;

use Swift_Mailer as SwiftMailer;
use Swift_Message as SwiftMessage;

class EmailQueue
{
    public function __construct(SwiftMailer $mailer){
        $this->mailer = $mailer;
    }

    public function add(SwiftMessage $message){
        return $this->mailer->send($message);
    }
}