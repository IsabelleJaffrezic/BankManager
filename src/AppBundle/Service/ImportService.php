<?php

namespace AppBundle\Service;
use AppBundle\Entity\Compte;
use AppBundle\Entity\ModePaiement;
use AppBundle\Entity\Operation;
use DateTime;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Created by PhpStorm.
 * User: zazhu
 * Date: 07/04/16
 * Time: 21:10
 */
class ImportService
{
    private $om;

    public function __construct(ObjectManager $objectManager)
    {
        $this->om = $objectManager;
    }

    public function convertFile($pathFile, Compte $compte)
    {
        $file = file_get_contents($pathFile);
        $lines = explode("\n", $file);

        $nbLineConverties = 0;
        foreach ($lines as $key => $line) {
            if ($this->convertLine($line, $compte)) {
                $nbLineConverties++;
            }
        }

        return [
            'nbLine' => count($lines),
            'nbLineConvertie' => $nbLineConverties,
        ];
    }

    public function convertLine($line, Compte $compte)
    {
        try {
            $data = str_getcsv($line, ";");

            $modePaiementRepo = $this->om->getRepository('AppBundle:ModePaiement');
            $modePaiement = $modePaiementRepo->findOneBy(array('libelle' => $data[2]));
            if (!($modePaiement instanceof ModePaiement) && !empty($modePaiement)) {
                $modePaiement = new ModePaiement();
                $modePaiement->setLibelle($data[2]);

                $this->om->persist($modePaiement);
            }

            $dateOperation = DateTime::createFromFormat('d/m/Y', $data[0]);
            $dateValue = DateTime::createFromFormat('d/m/Y', $data[1]);

            $operation = new Operation();
            $operation->setCompte($compte);
            $operation->setDateOperation($dateOperation);
            $operation->setLibelle($data[3]);
            $operation->setMontant((float)$data[4]);
            $operation->setModePaiement($modePaiement);
            if ($dateValue instanceof DateTime) {
                $operation->setDateValeur($dateValue);
            }

            $this->om->persist($operation);
            $this->om->flush();

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}