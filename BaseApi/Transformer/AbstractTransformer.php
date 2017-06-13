<?php

namespace OpenOrchestra\BaseApi\Transformer;

use OpenOrchestra\BaseApi\Exceptions\TransformerParameterTypeException;
use OpenOrchestra\BaseApi\Exceptions\HttpException\FacadeClassNotSetException;
use OpenOrchestra\BaseApi\Facade\FacadeInterface;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

use Doctrine\Common\Cache\ArrayCache;

/**
 * Class AbstractTransformer
 */
abstract class AbstractTransformer implements TransformerInterface
{
    protected $facadeClass;
    
    /**
     * @var TransformerManager
     */
    protected $context;
    protected $arrayCache;

    /**
     * @param ArrayCache $arrayCache
     * @param string     $facadeClass
     */
    public function __construct(
        ArrayCache $arrayCache,
        $facadeClass = null
    ) {
        $this->arrayCache = $arrayCache;
        $this->facadeClass = $facadeClass;
    }

    /**
     * @param TransformerManager $manager
     */
    public function setContext(TransformerManager $manager)
    {
        $this->context = $manager;
    }

    /**
     * @param string $name
     *
     * @return TransformerInterface
     */
    protected function getTransformer($name)
    {
        return $this->context->get($name);
    }

    /**
     * @return UrlGenerator
     */
    protected function getRouter()
    {
        return $this->context->getRouter();
    }

    /**
     * @param string         $name
     * @param array          $parameter
     * @param boolean|string $referenceType
     *
     * @return string
     */
    protected function generateRoute($name, $parameter = array(), $referenceType = UrlGeneratorInterface::ABSOLUTE_URL)
    {
        return $this->getRouter()->generate($name, $parameter, $referenceType);
    }

    /**
     * @param string $group
     *
     * @return bool
     */
    protected function hasGroup($group)
    {
        return $this->context->getGroupContext()->hasGroup($group);
    }

    /**
     * @param mixed $mixed
     *
     * @return FacadeInterface
     */
    public function cacheTransform($mixed)
    {
        $isObject = is_object($mixed);
        if ($isObject) {
            $id = spl_object_hash($mixed) . '-' . spl_object_hash($this->context->getGroupContext());
            if ($this->arrayCache->contains($id)) {
                return $this->arrayCache->fetch($id);
            }
        }
        $transformation = $this->transform($mixed);
        if ($isObject) {
            $this->arrayCache->save($id, $transformation);
        }

        return $transformation;
    }

    /**
     * @param mixed $mixed
     *
     * @return FacadeInterface
     */
    public function transform($mixed)
    {
    }

    /**
     * @param FacadeInterface $facade
     * @param mixed|null      $source
     *
     * @return mixed
     */
    public function cacheReverseTransform(FacadeInterface $facade, $source = null)
    {
        $id = spl_object_hash($facade) . '-' . spl_object_hash($this->context->getGroupContext());
        if (is_object($source)) {
            $id .= '-' . spl_object_hash($source);
        }
        if ($this->arrayCache->contains($id)) {
            return $this->arrayCache->fetch($id);
        }
        $reverseTransformation = $this->reverseTransform($facade, $source);
        $this->arrayCache->save($id, $reverseTransformation);

        return $reverseTransformation;
    }

    /**
     * @param FacadeInterface $facade
     * @param mixed|null      $source
     *
     * @return mixed
     */
    public function reverseTransform(FacadeInterface $facade, $source = null)
    {
    }

    /**
     * @return mixed
     *
     * @throws FacadeClassNotSetException
     * @throws TransformerParameterTypeException
     */
    protected function newFacade()
    {
        if (null === $this->facadeClass) {
            throw new FacadeClassNotSetException();
        }

        $facade = new $this->facadeClass();

        if (!$facade instanceof FacadeInterface) {
            throw new TransformerParameterTypeException();
        }

        return $facade;
    }
}
