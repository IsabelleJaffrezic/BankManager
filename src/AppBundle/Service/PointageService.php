<?php
/**
 * Created by PhpStorm.
 * User: zazhu
 * Date: 17/04/16
 * Time: 19:51
 */

namespace AppBundle\Service;


use AppBundle\Entity\Operation;
use AppBundle\Entity\Pointage;
use AppBundle\Entity\PointageOperation;
use Doctrine\Common\Persistence\ObjectManager;

class PointageService
{
    private $om;
    private $repo;

    public function __construct(ObjectManager $objectManager)
    {
        $this->om = $objectManager;
        $this->repo = $objectManager->getRepository('AppBundle:Pointage');
    }

    /**
     * @param Operation $operation
     * @return Pointage|null
     */
    public function matching(Operation $operation)
    {
        $like = $this->repo->findLikeLibelle($operation->getLibelle());
        $levenstein = null;
        foreach ($like as $item) {
            if ($levenstein === null) {
                $levenstein = $item;
            }
            if (levenshtein($levenstein->getLibelle(), $item->getLibelle()) > levenshtein($operation->getLibelle(), $item->getLibelle())) {
                $levenstein = $item;
            }
        }
        return $levenstein;
    }

    /**
     * @param PointageOperation $pointageOperation
     */
    public function pointer(PointageOperation $pointageOperation)
    {
        $operation = $pointageOperation->getOperation();
        $operation->setCategorie($pointageOperation->getCategorie());

        $this->om->persist($operation);
        $this->om->flush();
    }
}