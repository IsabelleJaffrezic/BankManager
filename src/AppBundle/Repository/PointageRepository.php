<?php

namespace AppBundle\Repository;
use AppBundle\Entity\Pointage;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;

/**
 * Class PointageRepository
 * @package AppBundle\Repository
 */
class PointageRepository extends EntityRepository
{
    /**
     * @param $libelle
     * @return Pointage|null
     * @throws NonUniqueResultException
     */
    public function findLikeLibelle($libelle)
    {
        $qb = $this
            ->createQueryBuilder('p')
            ->where(":libelle LIKE CONCAT('%',p.libelle,'%')")->setParameter('libelle', $libelle);

        return $qb->getQuery()->getResult();
    }
}
