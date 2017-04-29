<?php

namespace AppBundle\Command;

use AppBundle\Entity\Role;
use AppBundle\Entity\User;
use AppBundle\Helper\RemindMeal;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class RemindMealIntakeCommand extends ContainerAwareCommand
{
    public function configure()
    {
        $this
            ->setName('app:remind:meal-intake')
            ->setDescription('Remind a user about meal intake');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getContainer();
        /** @var RemindMeal $remindHelper */
        $remindHelper = $container->get('app.helper.remind_meal');

        $eatingType = ucfirst($remindHelper->getEatingTypeByTime());
        $emails = $remindHelper->getUsersEmailsForNotify();

        foreach($emails as $email){
            $container->get('app.notifier.remind_eating')->sendEmail($email, $eatingType);
        }

        $output->writeln("<info>Emails have been sent!</info>");
    }
}
