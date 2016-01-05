<?php

namespace OpenOrchestra\BaseApi\Transformer;

use OpenOrchestra\BaseApi\Facade\FacadeInterface;

/**
 * Interface TransformerWithContextInterface
 */
interface TransformerWithContextInterface extends TransformerInterface
{
    /**
     * @param mixed|null      $context
     * @param FacadeInterface $facade
     * @param mixed|null      $source
     *
     * @return mixed
     */
    public function reverseTransformWithContext($context, FacadeInterface $facade, $source = null);

}