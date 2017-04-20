<?php

use mCzolko\HerokuSchedulerBundle\Events;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class HerokuSchedulerSubscriber implements EventSubscriberInterface
{

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
        // https://www.youtube.com/watch?v=lxptFSJJ14Y
    }
}