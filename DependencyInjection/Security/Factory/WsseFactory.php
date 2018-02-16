<?php 

namespace Stadline\WSSESecurityBundle\DependencyInjection\Security\Factory;

use Symfony\Component\Config\Definition\Builder\NodeDefinition;
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
        
        $container
            ->setDefinition($providerId, new DefinitionDecorator('wsse.security.authentication.provider'))
            ->replaceArgument(0, new Reference($userProvider));
        
        $listenerId = 'security.authentication.listener.wsse.'.$id;
        $container->setDefinition(
            $listenerId,
            new DefinitionDecorator('wsse.security.authentication.listener')
        );
        
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
