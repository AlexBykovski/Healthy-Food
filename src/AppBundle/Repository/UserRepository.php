<?php

namespace AppBundle\Repository;

use AppBundle\Entity\User;
use \DateTime;
use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository
{
    public function findUserToRemindEating($minCountEating)
    {
        return $this->createQueryBuilder('u')
            ->select('u.email')
            ->join('u.dietAdditionalInformation', 'dai')
            ->where('dai.countEating >= :minCountEating')
            ->setParameter('minCountEating', $minCountEating)
            ->getQuery()
            ->getResult();
    }
}