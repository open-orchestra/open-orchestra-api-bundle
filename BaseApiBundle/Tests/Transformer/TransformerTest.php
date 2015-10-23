<?php

namespace OpenOrchestra\BaseApiBundle\Tests\Transformer;

use OpenOrchestra\BaseApi\Transformer\TransformerManager;
use Phake;

/**
 * Test TransformerTestCase
 */
class TransformerTest extends \PHPUnit_Framework_TestCase
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
        $transformerManager = new TransformerManager($router, $groupContext);
        $transformerManager->addTransformer($transformer);

        $this->assertSame($transformerManager->get($name), $transformer);
        $this->assertSame($transformerManager->getRouter(), $router);
        $this->assertSame($transformerManager->getGroupContext(), $groupContext);
    }
}
