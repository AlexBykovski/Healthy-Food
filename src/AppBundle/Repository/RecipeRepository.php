<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class RecipeRepository extends EntityRepository
{
    public function getEatingForUserByDateAndType($eatingType)
    {
        return $this->createQueryBuilder('r')
            ->select('r.id, r.calories, r.portions')
            ->where('r.eatingType = :eatingType')
            ->setParameter('eatingType', $eatingType)
            ->getQuery()
            ->getResult();
    }

    public function getRecipesByIds(array $ids = [])
    {
        return $this->createQueryBuilder('r')
            ->select('r')
            ->where('r.id IN (:ids)')
            ->setParameter('ids', $ids)
            ->getQuery()
            ->getResult();
    }
}