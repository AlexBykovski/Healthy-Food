<?php

namespace AppBundle\Repository;

use AppBundle\Entity\User;
use \DateTime;
use Doctrine\ORM\EntityRepository;

class NotificationRepository extends EntityRepository
{
    public function getUnreadNotificationsByUser(User $user, $type = 'all')
    {
        $query = $this->createQueryBuilder('n')
            ->select('n')
            ->where('n.user = :user')
            ->andWhere('n.isRead = false')
            ->setParameter('user', $user);


        if($type !== "all"){
            $query = $query->andWhere('n.type = :type')
                ->setParameter('type', $type);
        }

        return $query
            ->orderBy("n.createdAt", "DESC")
            ->getQuery()
            ->getResult();
    }

    public function getFirstUnreadNotificationByUserAndType(User $user, $type)
    {
        return $this->createQueryBuilder('n')
            ->select('n')
            ->where('n.user = :user')
            ->andWhere('n.type = :type')
            ->andWhere('n.isRead = false')
            ->setParameter('user', $user)
            ->setParameter('type', $type)
            ->orderBy("n.createdAt", "DESC")
            ->setMaxResults( 1 )
            ->getQuery()
            ->getResult();
    }
}