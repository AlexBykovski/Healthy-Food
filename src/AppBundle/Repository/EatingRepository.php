<?php

namespace AppBundle\Repository;

use AppBundle\Entity\User;
use \DateTime;
use Doctrine\ORM\EntityRepository;

class EatingRepository extends EntityRepository
{
    //@todo change to check full data
    public function findEatingForUserByDateAndType(User $user, DateTime $date, $type)
    {
        return $this->createQueryBuilder('e')
            ->select('e')
            ->join('e.recipe', 'r')
            ->where('r.eatingType = :type')
            ->andWhere('e.user = :user')
            ->andWhere('DAY(e.date) = DAY(:date)')
            ->setParameter('type', $type)
            ->setParameter('user', $user)
            ->setParameter('date', $date)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findDailyEatingForUser(User $user, DateTime $date)
    {
        return $this->createQueryBuilder('e')
            ->select('e')
            ->join('e.recipe', 'r')
            ->andWhere('e.user = :user')
            ->andWhere('DAY(e.date) = DAY(:date)')
            ->setParameter('user', $user)
            ->setParameter('date', $date)
            ->getQuery()
            ->getResult();
    }
}