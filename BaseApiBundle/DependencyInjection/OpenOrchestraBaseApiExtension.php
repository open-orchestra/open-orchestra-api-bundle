<?php

namespace OpenOrchestra\BaseApiBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class OpenOrchestraBaseApiExtension extends Extension
{
    /**
     * Loads a specific configuration.
     *
     * @param array            $config    An array of configuration values
     * @param ContainerBuilder $container A ContainerBuilder instance
     *
     * @throws \InvalidArgumentException When provided tag is not defined in this extension
     *
     * @api
     */
    public function load(array $config, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $config);

        $container->setParameter('open_orchestra_api.controller.http_exception_controller', $config['http_exception_controller']);
        $container->setParameter('open_orchestra_api.token.expiration_time', $config['token_expiration_time']);
        $container->setParameter('open_orchestra_api.handlers.datetime.default_format', \DateTime::ISO8601);
        $container->setParameter('open_orchestra_api.handlers.datetime.default_timezone', date_default_timezone_get());
        $container->setParameter('open_orchestra_api.handlers.datetime.cdata', true);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
        $loader->load('transformer.yml');
        $loader->load('oauth2.yml');
        $loader->load('security.yml');
    }
}
