<?php

namespace Stadline\WSSESecurityBundle\DependencyInjection\Security\Factory;

use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\DependencyInjection\ChildDefinition;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\DefinitionDecorator;
use Symfony\Bundle\SecurityBundle\DependencyInjection\Security\Factory\SecurityFactoryInterface;

/**
 * {@inheritDoc}
 */
class WsseFactory implements SecurityFactoryInterface
{
    /**
     * {@inheritDoc}
     * @throws \Symfony\Component\DependencyInjection\Exception\BadMethodCallException
     * @throws \Symfony\Component\DependencyInjection\Exception\OutOfBoundsException
     */
    public function create(ContainerBuilder $container, $id, $config, $userProvider, $defaultEntryPoint)
    {
        $providerId = 'security.authentication.provider.wsse.'.$id;


        $listenerId = 'security.authentication.listener.wsse.'.$id;
        if(class_exists('Symfony\Component\DependencyInjection\DefinitionDecorator')){
            $container
                ->setDefinition($providerId, new DefinitionDecorator('wsse.security.authentication.provider'))
                ->replaceArgument(0, new Reference($userProvider));
            $container->setDefinition(
                $listenerId,
                new DefinitionDecorator('wsse.security.authentication.listener')
            );
        } else {
            $container
                ->setDefinition($providerId, new ChildDefinition('wsse.security.authentication.provider'))
                ->replaceArgument(0, new Reference($userProvider));
            $container->setDefinition(
                $listenerId,
                new ChildDefinition('wsse.security.authentication.listener')
            );
        }

        return array($providerId, $listenerId, $defaultEntryPoint);
    }

    /**
     * {@inheritDoc}
     */
    public function getPosition()
    {
        return 'pre_auth';
    }

    /**
     * {@inheritDoc}
     */
    public function getKey()
    {
        return 'wsse';
    }

    public function addConfiguration(NodeDefinition $node)
    {
    }
}
