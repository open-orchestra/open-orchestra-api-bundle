<?php

namespace OpenOrchestra\BaseApiBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class SerializerCompilerPass
 */
class SerializerCompilerPass implements CompilerPassInterface
{
    /**
     * You can modify the container here before it is dumped to PHP code.
     *
     * @param ContainerBuilder $container
     *
     * @api
     */
    public function process(ContainerBuilder $container)
    {
        $container->setParameter('open_orchestra_api.handlers.datetime.default_format', \DateTime::ISO8601);
        $container->setParameter('open_orchestra_api.handlers.datetime.default_timezone', date_default_timezone_get());
        $container->setParameter('open_orchestra_api.handlers.datetime.cdata', true);
    }

}
