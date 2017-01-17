<?php
/**
 * User: iyurin
 * Date: 13.11.16
 * Time: 14:43
 */

namespace VIS\Bundle\ApiBundle\Security\Factory;


use Symfony\Bundle\SecurityBundle\DependencyInjection\Security\Factory\SecurityFactoryInterface;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\DefinitionDecorator;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class TokenAuthFactory
 * @package VIS\Bundle\ApiBundle\Security\Factory
 */
class TokenAuthFactory implements SecurityFactoryInterface
{

    public function create(ContainerBuilder $container, $id, $config, $userProvider, $defaultEntryPoint)
    {
        $providerId = 'security.authentication.provider.token_auth.'.$id;
        $container
            ->setDefinition($providerId, new DefinitionDecorator('vis_api.security.token_auth.provider'))
            ->replaceArgument(0, new Reference($userProvider))
        ;

        $listenerId = 'security.authentication.listener.token_auth.'.$id;
        $container->setDefinition($listenerId, new DefinitionDecorator('vis_api.security.token_auth.listener'));

        return array($providerId, $listenerId, $defaultEntryPoint);
    }

    public function getPosition()
    {
        return 'pre_auth';
    }

    public function getKey()
    {
        return 'token_auth';
    }

    public function addConfiguration(NodeDefinition $builder)
    {
        // TODO: Implement addConfiguration() method.
    }
}