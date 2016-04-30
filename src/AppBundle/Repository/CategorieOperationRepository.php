<?php

namespace AppBundle\Repository;
use Doctrine\ORM\EntityRepository;

/**
 * CategorieOperationRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CategorieOperationRepository extends EntityRepository
{
    public function findAllOrderByParent()
    {
        $qb = $this->createQueryBuilder('c');
        
        return $qb->getQuery()->getResult();
    }
}
