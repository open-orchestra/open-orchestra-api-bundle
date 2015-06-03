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
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $factoryService = $config['factory_service'];
        if (is_null($factoryService) && class_exists('Doctrine\ODM\MongoDB\DocumentManager')) {
            $factoryService = 'doctrine.odm.mongodb.document_manager';
        } elseif (is_null($factoryService) && class_exists('Doctrine\ORM\EntityManager')) {
            $factoryService = 'doctrine.orm.entity_manager';
        }

        foreach ($config['document'] as $class => $content) {
            if (is_array($content)) {
                $container->setParameter('open_orchestra_api.document.' . $class . '.class', $content['class']);
                if (array_key_exists('repository', $content)) {
                    $container->register('open_orchestra_api.repository.' . $class, $content['repository'])
                        ->setFactoryService($factoryService)
                        ->setFactoryMethod('getRepository')
                        ->addArgument($content['class']);
                }
            }
        }

        $container->setParameter('open_orchestra_api.controller.http_exception_controller', $config['http_exception_controller']);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
        $loader->load('transformer.yml');
        $loader->load('oauth2.yml');
        $loader->load('security.yml');
    }
}
