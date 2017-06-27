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
     * @deprecated  use directly OpenOrchestra\BaseApi\Transformer\TransformerManager::transform and OpenOrchestra\BaseApi\Transformer\TransformerManager::reverseTransform
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

    /**
     * @param string $name
     * @param mixed  $entity
     * @param mixed  $params
     *
     * @return FacadeInterface
     */
    public function transform($name, $entity, $params = null) {
        $transformer = $this->transformers[$name];

        if (!is_object($entity) || !$transformer->isCached()) {
            return $transformer->transform($entity, $params);
        }

        $id = spl_object_hash($entity) . '-' . spl_object_hash($this->groupContext);
        if ($this->arrayCache->contains($id)) {
            return $this->arrayCache->fetch($id);
        }
        $transformation = $transformer->transform($entity, $params);
        $this->arrayCache->save($id, $transformation);

        return $transformation;
    }

    /**
     * @param string $name
     * @param mixed  $entity
     * @param mixed  $params
     *
     * @return mixed
     */
    public function reverseTransform($name, $entity, $params = null) {
        return $this->transformers[$name]->reverseTransform($entity, $params);
    }
}
