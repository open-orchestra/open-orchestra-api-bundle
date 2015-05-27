<?php

namespace OpenOrchestra\BaseApiBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {
 * @link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class
 * }
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('open_orchestra_model');

        $rootNode->children()
            ->scalarNode('factory_service')->defaultNull()->end()
            ->arrayNode('document')
                ->addDefaultsIfNotSet()
                ->children()
                    ->arrayNode('api_client')
                        ->addDefaultsIfNotSet()
                        ->children()
                            ->scalarNode('class')->defaultValue('OpenOrchestra\BaseApiBundle\Document\ApiClient')->end()
                            ->scalarNode('repository')->defaultValue('OpenOrchestra\BaseApiBundle\Repository\ApiClientRepository')->end()
                        ->end()
                    ->end()
                    ->arrayNode('access_token')
                        ->addDefaultsIfNotSet()
                        ->children()
                            ->scalarNode('class')->defaultValue('OpenOrchestra\BaseApiBundle\Document\AccessToken')->end()
                            ->scalarNode('repository')->defaultValue('OpenOrchestra\BaseApiBundle\Repository\AccessTokenRepository')->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ->end();

        return $treeBuilder;
    }
}
