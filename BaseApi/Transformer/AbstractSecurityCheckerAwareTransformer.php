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
     * @param AuthorizationCheckerInterface $authorizationChecker
     */
    public function __construct(AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->authorizationChecker = $authorizationChecker;
    }
}
