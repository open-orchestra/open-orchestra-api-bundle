<?php

namespace OpenOrchestra\BaseApiBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/**
 * Class OpenOrchestraBaseApiExtensionTest
 */
class OpenOrchestraBaseApiExtensionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test default value configuration
     */
    public function testDefaultConfig()
    {
        $container = $this->loadContainerFromFile('empty');
        $this->assertEquals('OpenOrchestra\BaseApiBundle\Controller\ExceptionController::showAction', $container->getParameter('open_orchestra_api.controller.http_exception_controller'));
        $this->assertEquals('+1month', $container->getParameter('open_orchestra_api.token.expiration_time'));

    }

    /**
     * Test configuration with value
     */
    public function testConfigWithValue()
    {
        $container = $this->loadContainerFromFile('value');

        $this->assertEquals('fakeController:fakeAction', $container->getParameter('open_orchestra_api.controller.http_exception_controller'));
        $this->assertEquals('fake_month', $container->getParameter('open_orchestra_api.token.expiration_time'));
    }

    /**
     * @param string $file
     *
     * @return ContainerBuilder
     */
    private function loadContainerFromFile($file)
    {
        $container = new ContainerBuilder();
        $container->setParameter('kernel.debug', false);
        $container->setParameter('kernel.cache_dir', '/tmp');
        $container->registerExtension(new OpenOrchestraBaseApiExtension());

        $locator = new FileLocator(__DIR__ . '/Fixtures/config/');
        $loader = new YamlFileLoader($container, $locator);
        $loader->load($file . '.yml');
        $container->getCompilerPassConfig()->setOptimizationPasses(array());
        $container->getCompilerPassConfig()->setRemovingPasses(array());
        $container->compile();

        return $container;
    }
}
