<?php
namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\HttpKernel\Config\FileLocator;

/**
 * Created by PhpStorm.
 * User: zazhu
 * Date: 07/04/16
 * Time: 21:14
 */
class ImportCommand extends ContainerAwareCommand
{
    public function configure()
    {
        $this
            ->setName('import:compte')
            ->setDescription('Importation d\'un compte et opérations');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $importService = $this->getContainer()->get('app.service.import');

        $finder = new Finder();
        $finder->files()->in(__DIR__.'/../../../app/Resources/imports')->name('*.csv');

        /** @var SplFileInfo $file */
        foreach ($finder as $file) {
            $output->writeln('<comment>Conversion du fichier : '.$file->getRealPath().'</comment>');
            $result = $importService->convertFile($file->getRealPath());
            $output->writeln('-> Nb lignes dans le fichier : '.$result['nbLine']);
            $output->writeln('-> Nb lignes converties : '.$result['nbLineConvertie']);
        }

        $output->writeln('<info>Import terminé</info>');
    }
}