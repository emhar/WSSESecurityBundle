<?php

namespace Stadline\WSSESecurityBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * {@inheritDoc}
 */
class SecurityContextDeprecatedPass implements CompilerPassInterface
{
    /**
     * {@inheritDoc}
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException
     * @throws \Symfony\Component\DependencyInjection\Exception\OutOfBoundsException
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('security.context')) {
            $container->getDefinition('wsse.security.authentication.listener')
                ->replaceArgument(0, new Reference('security.token_storage'));
        }
    }
}
