<?php

namespace OpenOrchestra\BaseApi\Transformer;

use OpenOrchestra\BaseApi\Facade\FacadeInterface;

/**
 * Interface TransformerInterface
 */
interface TransformerInterface
{
    /**
     * @param mixed $mixed
     *
     * @return FacadeInterface
     */
    public function cacheTransform($mixed);

    /**
     * @param mixed $mixed
     *
     * @return FacadeInterface
     */
    public function transform($mixed);

    /**
     * @param FacadeInterface $facade
     * @param mixed|null      $source
     *
     * @return mixed
     */
    public function cacheReverseTransform(FacadeInterface $facade, $source = null);

    /**
     * @param FacadeInterface $facade
     * @param mixed|null      $source
     *
     * @return mixed
     */
    public function reverseTransform(FacadeInterface $facade, $source = null);

    /**
     * @return string
     */
    public function getName();

    /**
     * @param TransformerManager $manager
     */
    public function setContext(TransformerManager $manager);
}
