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

    public function sendEmail($email, $type)
    {
        $message = $this->emailBuilder
            ->setSubject('Healthy Food')
            ->setTo($email)
            ->setTemplate('/email/remind-eating.html.twig')
            ->setTemplateVariables(["eatingType" => $type])
            ->build();

        $this->emailQueue->add($message);
    }
}
