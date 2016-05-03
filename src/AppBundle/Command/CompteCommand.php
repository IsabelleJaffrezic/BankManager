<?php
namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Created by PhpStorm.
 * User: zazhu
 * Date: 07/04/16
 * Time: 21:14
 */
class CompteCommand extends ContainerAwareCommand
{
    public function configure()
    {
        $this
            ->setName('compte:create')
            ->setDescription('Création d\'un compte')
            ->addArgument('libelle', InputArgument::REQUIRED, 'Libellé du compte')
            ->addArgument('soldeInitial', InputArgument::REQUIRED, 'Solde initial du compte')
            ->addOption('import', 'i', InputOption::VALUE_OPTIONAL, 'Chemin d\'un fichier d\'importation à la création')
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $compteService = $this->getContainer()->get('app.service.compte');
        $compte = $compteService->createCompte($input->getArgument('libelle'), $input->getArgument('soldeInitial'));

        $output->writeln($input->getOption('import'));
    }
}