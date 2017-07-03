<?php

namespace OpenOrchestra\BaseApi\Transformer;

use OpenOrchestra\BaseApi\Facade\FacadeInterface;

/**
 * Interface TransformerInterface
 */
interface TransformerInterface
{
    /**
     * @param mixed      $mixed
     * @param array|null $params
     *
     * @return FacadeInterface
     */
    public function transform($mixed, array $params = null);

    /**
     * @param FacadeInterface $facade
     * @param array|null      $params
     *
     * @return mixed
     */
    public function reverseTransform(FacadeInterface $facade, array $params = null);

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
