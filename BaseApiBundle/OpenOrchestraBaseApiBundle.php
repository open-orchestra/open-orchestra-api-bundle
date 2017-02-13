<?php

namespace OpenOrchestra\BaseApiBundle;

use OpenOrchestra\BaseApiBundle\DependencyInjection\Compiler\Oauth2CompilerPass;
use OpenOrchestra\BaseApiBundle\DependencyInjection\Compiler\TransformerCompilerPass;
use OpenOrchestra\BaseApiBundle\DependencyInjection\Security\Factory\OAuth2Factory;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use OpenOrchestra\BaseApiBundle\DependencyInjection\Compiler\SerializerCompilerPass;

/**
 * Class OpenOrchestraBaseApiBundle
 */
class OpenOrchestraBaseApiBundle extends Bundle
{
    /**
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new TransformerCompilerPass());
        $container->addCompilerPass(new Oauth2CompilerPass());
        $container->addCompilerPass(new SerializerCompilerPass());

        $extension = $container->getExtension('security');
        $extension->addSecurityListenerFactory(new OAuth2Factory());
    }
}
