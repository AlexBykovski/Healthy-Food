<?php

namespace AppBundle\Repository;

use AppBundle\Entity\User;
use \DateTime;
use Doctrine\ORM\EntityRepository;

class RecipeProductRepository extends EntityRepository
{
    //@todo change to check full data
    public function getNameAllProducts()
    {
        return $this->createQueryBuilder('rp')
            ->select('DISTINCT rp.name')
            ->getQuery()
            ->getResult();
    }
}