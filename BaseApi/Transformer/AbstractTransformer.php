<?php

namespace OpenOrchestra\BaseApi\Transformer;

use OpenOrchestra\BaseApi\Exceptions\TransformerParameterTypeException;
use OpenOrchestra\BaseApi\Exceptions\HttpException\FacadeClassNotSetException;
use OpenOrchestra\BaseApi\Facade\FacadeInterface;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

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
     * @param string     $facadeClass
     */
    public function __construct(
        $facadeClass = null
    ) {
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
            if ($this->context->getArrayCache()->contains($id)) {
                return $this->context->getArrayCache()->fetch($id);
            }
        }
        $transformation = $this->transform($mixed);
        if ($isObject) {
            $this->context->getArrayCache()->save($id, $transformation);
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
