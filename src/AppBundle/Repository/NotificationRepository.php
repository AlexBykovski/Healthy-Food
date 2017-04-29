<?php

namespace AppBundle\Repository;

use AppBundle\Entity\User;
use \DateTime;
use Doctrine\ORM\EntityRepository;

class NotificationRepository extends EntityRepository
{
    public function getCountUnreadNotificationByUser(User $user, $type)
    {
        $query = $this->createQueryBuilder('n')
            ->select('n.id')
            ->where('n.user = :user')
            ->setParameter('user', $user);


        if($type !== "all"){
            $query = $query->andWhere('e.type = :type')
                ->setParameter('type', $type);
        }

        return $query->getQuery()
            ->getResult();
    }
}