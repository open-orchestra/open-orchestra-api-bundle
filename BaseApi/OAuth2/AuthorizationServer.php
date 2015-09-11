<?php

namespace OpenOrchestra\BaseApi\OAuth2;

use OpenOrchestra\BaseApi\Exceptions\HttpException\AuthorizationNonSupportedHttpException;
use OpenOrchestra\BaseApi\Facade\FacadeInterface;
use OpenOrchestra\BaseApi\OAuth2\Strategy\StrategyInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * Class AuthorizationServer
 */
class AuthorizationServer
{
    protected $strategies;

    /**
     * @param StrategyInterface $strategy
     */
    public function addStrategy(StrategyInterface $strategy)
    {
        $this->strategies[$strategy->getName()] = $strategy;
    }

    /**
     * @param Request $request
     *
     * @return ConstraintViolationListInterface|FacadeInterface
     * @throws AuthorizationNonSupportedHttpException
     */
    public function requestToken(Request $request)
    {
        /** @var StrategyInterface $strategy */
        foreach ($this->strategies as $strategy) {
            if ($strategy->supportRequestToken($request)) {
                return $strategy->requestToken($request);
            }
        }

        throw new AuthorizationNonSupportedHttpException();
    }
}
