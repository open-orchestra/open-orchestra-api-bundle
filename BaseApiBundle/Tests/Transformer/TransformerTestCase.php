<?php

namespace OpenOrchestra\BaseApi\Tests\Transformer;

use Phake;

/**
 * Test TransformerTestCase
 */
abstract class TransformerTestCase extends \PHPUnit_Framework_TestCase
{
    protected $transformerManager;

    protected $router;

    protected $groupContext;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->router = Phake::mock('Symfony\Component\Routing\RouterInterface');
        $this->groupContext = Phake::mock('OpenOrchestra\BaseApi\Context\GroupContext');
        $this->transformerManager = Phake::mock('OpenOrchestra\BaseApi\Transformer\TransformerManager');

        Phake::when($this->router)
             ->generate(Phake::anyParameters())
             ->thenReturn('route');

        Phake::when($this->transformerManager)
             ->getRouter()
             ->thenReturn($this->router);

        Phake::when($this->transformerManager)
             ->getGroupContext()
             ->thenReturn($this->groupContext);
    }
}
