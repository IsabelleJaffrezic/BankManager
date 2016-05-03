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

        if ($flush) {
            $this->om->persist($compte);
            $this->om->flush();
        }

        return $compte;
    }
}
