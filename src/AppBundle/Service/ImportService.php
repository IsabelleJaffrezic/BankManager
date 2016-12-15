<?php

namespace AppBundle\Service;
use AppBundle\Entity\Compte;
use AppBundle\Entity\ModePaiement;
use AppBundle\Entity\Operation;
use DateTime;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Created by PhpStorm.
 * User: zazhu
 * Date: 07/04/16
 * Time: 21:10
 */
class ImportService
{
    private $om;
    private $operationRepo;
    private $result;
    private $output;

    public function __construct(ObjectManager $objectManager)
    {
        $this->om = $objectManager;
        $this->operationRepo = $this->om->getRepository('AppBundle:Operation');
        $this->result = [
            'nbLine' => 0,
            'nbLineConvertie' => 0,
            'nbDoublon' => 0,
            'nbException' => 0,
        ];
    }

    public function convertFile($pathFile, Compte $compte, OutputInterface $output = null)
    {
        $file = file_get_contents($pathFile);
        $lines = explode("\n", $file);

        foreach ($lines as $key => $line) {
            $this->convertLine($line, $compte, $output);
        }

        return $this->result;
    }

    public function convertLine($line, Compte $compte, OutputInterface $output = null)
    {
        try {
            $data = str_getcsv($line, ";");

            $modePaiementRepo = $this->om->getRepository('AppBundle:ModePaiement');
            $modePaiement = $modePaiementRepo->findOneBy(array('libelle' => $data[2]));
            if (!$modePaiement instanceof ModePaiement) {
                $modePaiement = new ModePaiement();
                $modePaiement->setLibelle($data[2]);

                $this->om->persist($modePaiement);
                $this->om->flush();
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

            if (null === $this->operationRepo->findOperation($operation)) {
                $this->om->persist($operation);
                $this->om->flush();

                $this->result['nbLineConvertie']++;
            } else {
                $this->result['nbDoublon']++;
            }
            return true;
        } catch (\Exception $e) {
            if ($output instanceof OutputInterface) {
                $output->writeln('<error>'.$e->getMessage().'</error>');
            }
            $this->result['nbException']++;
            return false;
        }
    }
}