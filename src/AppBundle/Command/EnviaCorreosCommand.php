<?php

namespace AppBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;


class EnviaCorreosCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            // the name of the command (the part after "app/console")
            ->setName('app:envia-correos')

            // the short description shown while running "php app/console list"
            ->setDescription('Envia correos de la consulta')
//            ->addArgument('prueba', InputArgument::REQUIRED, 'The username of the user.')

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('Envia correo a los académicos para la consulta')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // ...
        $em = $this->getContainer()->get('doctrine')->getManager();
        $q = $em->createQueryBuilder();
        $q->select('a')->from('AppBundle:Academico', 'a');
        $academicos = $q->getQuery()->getResult();

        foreach($academicos as $academico) {

            $output->writeln('http://gaspacho.matmor.unam.mx/survey/respuesta/'.$academico->getSlug().'/' . $academico->getToken().'/new');
        }
    }
}
