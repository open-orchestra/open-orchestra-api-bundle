<?php

namespace OpenOrchestra\BaseApi\Transformer;

use Doctrine\Common\Cache\ArrayCache;
use OpenOrchestra\BaseApi\Context\GroupContext;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Class TransformerManager
 */
class TransformerManager
{
    protected $transformers = array();
    protected $router;
    protected $groupContext;
    protected $arrayCache;

    /**
     * @param UrlGeneratorInterface $router
     * @param GroupContext          $groupContext
     */
    public function __construct(
        UrlGeneratorInterface $router,
        GroupContext $groupContext,
        ArrayCache $arrayCache)
    {
        $this->router = $router;
        $this->groupContext = $groupContext;
        $this->arrayCache = $arrayCache;
    }

    /**
     * @param TransformerInterface $transformer
     */
    public function addTransformer(TransformerInterface $transformer)
    {
        $this->transformers[$transformer->getName()] = $transformer;
        $transformer->setContext($this);
    }

    /**
     * @param string $name
     *
     * @return TransformerInterface
     */
    public function get($name)
    {
        return $this->transformers[$name];
    }

    /**
     * @return UrlGeneratorInterface
     */
    public function getRouter()
    {
        return $this->router;
    }

    /**
     * @return GroupContext
     */
    public function getGroupContext()
    {
        return $this->groupContext;
    }

    /**
     * @return ArrayCache
     */
    public function getArrayCache()
    {
        return $this->arrayCache;
    }
}
