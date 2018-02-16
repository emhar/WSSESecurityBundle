<?php

namespace Stadline\WSSESecurityBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * {@inheritDoc}
 */
class RenewSecretPartnerCommand extends ContainerAwareCommand
{

    /**
     * {@inheritDoc}
     * @throws \Symfony\Component\Console\Exception\InvalidArgumentException
     */
    protected function configure()
    {
        $this->setName('wsse:partner:renew-secret')
                ->addArgument(
                        'login', InputArgument::REQUIRED, 'login')
        ;
    }

    /**
     * {@inheritDoc}
     * @throws \Doctrine\ORM\ORMInvalidArgumentException
     * @throws \Symfony\Component\Console\Exception\InvalidArgumentException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \LogicException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceCircularReferenceException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $partnerManager = $this->getContainer()->get('stadline.wsse.partner_manager');
        
        $login = $input->getArgument('login');
        
        $partner = $partnerManager->renewSecret($login);
        
        if($partner) {
            $output->writeln('Partner Secret has been renewed : ');
            $output->writeln('name : ' .$partner->getName());
            $output->writeln('login : ' .$login);
            $output->writeln('secret : ' .$partner->getSecret(), OutputInterface::OUTPUT_PLAIN);
        } else {
            $output->writeln('Partner not found');
        }
        
    }

}
