<?php

namespace OpenOrchestra\BaseApi\Transformer;

use Doctrine\Common\Cache\ArrayCache;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class AbstractSecurityCheckerAwareTransformer
 */
abstract class AbstractSecurityCheckerAwareTransformer extends AbstractTransformer
{
    /**
     * @var AuthorizationCheckerInterface
     */
    protected $authorizationChecker;

    /**
     * @param ArrayCache                    $arrayCache
     * @param string                        $facadeClass
     * @param AuthorizationCheckerInterface $authorizationChecker
     */
    public function __construct(
        ArrayCache $arrayCache,
        $facadeClass = null,
        AuthorizationCheckerInterface $authorizationChecker)
    {
        parent::__construct($arrayCache, $facadeClass);
        $this->authorizationChecker = $authorizationChecker;
    }
}
