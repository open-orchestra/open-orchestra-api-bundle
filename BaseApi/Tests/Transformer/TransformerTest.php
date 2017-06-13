<?php

namespace OpenOrchestra\BaseApi\Tests\Transformer;

use OpenOrchestra\BaseApi\Transformer\TransformerManager;
use OpenOrchestra\BaseBundle\Tests\AbstractTest\AbstractBaseTestCase;
use Phake;

/**
 * Test TransformerTestCase
 */
class TransformerTest extends AbstractBaseTestCase
{
    /**
     * test TransformerManager class
     */
    public function testTransformerManager()
    {
        $name = 'first_transfomer';
        $transformer = Phake::mock('OpenOrchestra\BaseApi\Transformer\TransformerInterface');
        Phake::when($transformer)->getName()
            ->thenReturn($name);

        $router = Phake::mock('Symfony\Component\Routing\RouterInterface');
        $groupContext = Phake::mock('OpenOrchestra\BaseApi\Context\GroupContext');
        $arrayCache = Phake::mock('Doctrine\Common\Cache\ArrayCache');
        $transformerManager = new TransformerManager($router, $groupContext, $arrayCache);
        $transformerManager->addTransformer($transformer);

        $this->assertSame($transformerManager->get($name), $transformer);
        $this->assertSame($transformerManager->getRouter(), $router);
        $this->assertSame($transformerManager->getGroupContext(), $groupContext);
        $this->assertSame($transformerManager->getArrayCache(), $arrayCache);
    }
}
