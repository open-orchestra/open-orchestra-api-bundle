<?php

namespace OpenOrchestra\BaseApiModelBundle;

use OpenOrchestra\BaseApiModelBundle\DependencyInjection\Compiler\EntityResolverCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class OpenOrchestraBaseApiModelBundle
 */
class OpenOrchestraBaseApiModelBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new EntityResolverCompilerPass());
    }
}
