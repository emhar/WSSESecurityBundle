<?php

namespace Stadline\WSSESecurityBundle;

use Stadline\WSSESecurityBundle\DependencyInjection\Compiler\SecurityContextDeprecatedPass;
use Stadline\WSSESecurityBundle\DependencyInjection\Security\Factory\WsseFactory;
use Symfony\Bundle\SecurityBundle\DependencyInjection\SecurityExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * {@inheritDoc}
 */
class StadlineWSSESecurityBundle extends Bundle
{
    /**
     * {@inheritDoc}
     * @throws \Symfony\Component\DependencyInjection\Exception\LogicException
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $extension = $container->getExtension('security');
        /* @var $extension SecurityExtension */
        $extension->addSecurityListenerFactory(new WsseFactory());

        $container->addCompilerPass(new SecurityContextDeprecatedPass());
    }
}
