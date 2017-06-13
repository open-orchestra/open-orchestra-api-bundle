<?php

namespace OpenOrchestra\BaseApi\Transformer;

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
     * @param string                        $facadeClass
     * @param AuthorizationCheckerInterface $authorizationChecker
     */
    public function __construct(
        $facadeClass = null,
        AuthorizationCheckerInterface $authorizationChecker)
    {
        parent::__construct($facadeClass);
        $this->authorizationChecker = $authorizationChecker;
    }
}
