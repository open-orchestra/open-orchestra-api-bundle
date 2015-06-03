<?php

namespace OpenOrchestra\BaseApi\Security\Authentication\Provider;

use OpenOrchestra\BaseApi\Exceptions\HttpException\TokenBlockedHttpException;
use OpenOrchestra\BaseApi\Exceptions\HttpException\TokenExpiredHttpException;
use OpenOrchestra\BaseApi\Exceptions\HttpException\UserNotFoundHttpException;
use OpenOrchestra\BaseApi\Manager\AccessTokenManager;
use OpenOrchestra\BaseApi\Security\Authentication\Token\OAuth2Token;
use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

/**
 * Class OAuth2AuthenticationProvider
 */
class OAuth2AuthenticationProvider implements AuthenticationProviderInterface
{
    protected $accessTokenManager;

    /**
     * @param AccessTokenManager $accessTokenManager
     */
    public function __construct(AccessTokenManager $accessTokenManager)
    {
        $this->accessTokenManager = $accessTokenManager;
    }

    /**
     * Attempts to authenticate a TokenInterface object.
     *
     * @param TokenInterface $token The TokenInterface instance to authenticate
     *
     * @throws TokenBlockedHttpException
     * @throws TokenExpiredHttpException
     * @throws UserNotFoundHttpException
     * @return TokenInterface An authenticated TokenInterface instance, never null
     */
    public function authenticate(TokenInterface $token)
    {
        $accessToken = $token->getAccessToken();
        $accessTokenEntity = $this->accessTokenManager->findOneByCode($accessToken);
        if (is_null($accessTokenEntity) || $accessTokenEntity->isBlocked()) {
            throw new TokenBlockedHttpException();
        }
        if ($accessTokenEntity->isExpired()) {
            throw new TokenExpiredHttpException();
        }

        $authenticatedToken = OAuth2Token::createFromAccessTokenEntity($accessTokenEntity);

        return $authenticatedToken;
    }

    /**
     * Checks whether this provider supports the given token.
     *
     * @param TokenInterface $token A TokenInterface instance
     *
     * @return Boolean true if the implementation supports the Token, false otherwise
     */
    public function supports(TokenInterface $token)
    {
        return $token instanceof OAuth2Token;
    }
}
