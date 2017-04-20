<?php
namespace AppBundle\Subscriber;

use AppBundle\Notifier\RemindEating;
use Doctrine\ORM\EntityManagerInterface;
use mCzolko\HerokuSchedulerBundle\Events;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

//https://github.com/mCzolko/HerokuSchedulerBundle
class HerokuSchedulerSubscriber implements EventSubscriberInterface
{
    /** @var RemindEating*/
    private $reminder;

    /** @var  EntityManagerInterface */
    private $em;

    public function __constructor(RemindEating $reminder, EntityManagerInterface $em){
        $this->reminder = $reminder;
        $this->em = $em;
    }

    public static function getSubscribedEvents()
    {
        return [
            Events::TEN_MINUTES => 'tenMinutes',
            Events::HOURLY      => 'hourly',
            Events::DAILY       => 'daily'
        ];
    }

    public function tenMinutes()
    {
        // Check notifications on your Apple watch
    }

    public function hourly()
    {
        // Send message at least one hot chick on Badoo
    }

    public function daily()
    {
        $this->reminder->sendEmail("bykovski.work@gmail.com");
    }
}