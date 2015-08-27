<?php

namespace OpenOrchestra\BaseApi\OAuth2\Strategy;

use OpenOrchestra\BaseApi\Facade\FacadeInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * Interface StrategyInterface
 */
interface StrategyInterface
{
    /**
     * @param Request $request
     *
     * @return boolean
     */
    public function supportRequestToken(Request $request);

    /**
     * @param Request $request
     *
     * @return ConstraintViolationListInterface|FacadeInterface
     */
    public function requestToken(Request $request);

    /**
     * @return string
     */
    public function getName();
}
