<?php

namespace AppBundle\Service;

use AppBundle\Entity\Compte;
use Doctrine\Common\Persistence\ObjectManager;

class CompteService
{
    private $om;

    public function __construct(ObjectManager $objectManager)
    {
        $this->om = $objectManager;
    }

    public function createCompte($libelle, $soldeInitial, $flush = true)
    {
        $compte = new Compte();
        $compte->setLibelle($libelle);
        $compte->setSoldeInitial($soldeInitial);
        $compte->setCurrentSolde(0);
        $compte->setSoldePrevisionnel(0);

        if ($flush) {
            $this->om->persist($compte);
            $this->om->flush();
        }

        return $compte;
    }

    public function find($id)
    {
        return $this->om->getRepository('AppBundle:Compte')->find($id);
    }
}
