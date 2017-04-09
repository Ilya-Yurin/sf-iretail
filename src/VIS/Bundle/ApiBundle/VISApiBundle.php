<?php

namespace VIS\Bundle\ApiBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use VIS\Bundle\ApiBundle\Security\Factory\TokenAuthFactory;

class VISApiBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $extension = $container->getExtension('security');
        $extension->addSecurityListenerFactory(new TokenAuthFactory());
    }
}
