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
     * @param array $params
     *
     * @return FacadeInterface
     */
    public function transform($mixed, array $params = array());

    /**
     * @param FacadeInterface $facade
     * @param array           $params
     *
     * @return mixed
     */
    public function reverseTransform(FacadeInterface $facade, array $params = array());

    /**
     * @return bool
     */
    public function isCached();

    /**
     * @return string
     */
    public function getName();

    /**
     * @param TransformerManager $manager
     */
    public function setContext(TransformerManager $manager);
}
