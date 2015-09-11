<?php

namespace OpenOrchestra\BaseApi\OAuth2\Strategy;

use OpenOrchestra\BaseApi\Exceptions\HttpException\TokenBlockedHttpException;
use OpenOrchestra\BaseApi\Exceptions\HttpException\TokenNotFoundHttpException;
use OpenOrchestra\BaseApi\Facade\FacadeInterface;
use OpenOrchestra\BaseApi\Facade\OAuth2\AccessTokenFacade;
use OpenOrchestra\BaseApi\Model\TokenInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * Class RefreshTokenStrategy
 */
class RefreshTokenStrategy extends AbstractStrategy
{
    /**
     * @param Request $request
     *
     * @return boolean
     */
    public function supportRequestToken(Request $request)
    {
        $client = $request->getUser() && $request->getPassword();
        $token = 'refresh_token' == $request->get('grant_type') && $request->get('refresh_token');

        return $client && $token;
    }

    /**
     * @param Request $request
     *
     * @return ConstraintViolationListInterface|FacadeInterface
     * @throws TokenNotFoundHttpException
     * @throws TokenBlockedHttpException
     */
    public function requestToken(Request $request)
    {
        $client = $this->getClient($request);
        $refreshToken = $request->get('refresh_token');

        /** @var TokenInterface $accessToken */
        $accessToken = $this->accessTokenRepository->findOneByClientAndRefreshToken($client, $refreshToken);

        if (! $accessToken instanceof TokenInterface) {
            throw new TokenNotFoundHttpException();
        }
        if ($accessToken->isBlocked()) {
            throw new TokenBlockedHttpException();
        }

        $newAccessToken = $this->accessTokenManager->create($client, $accessToken->getUser());
        if (!$newAccessToken->isValid($this->validator)) {
            return $newAccessToken->getViolations();
        }

        $this->accessTokenManager->save($newAccessToken);

        $tokenFacade = new AccessTokenFacade();
        $tokenFacade->accessToken   = $newAccessToken->getCode();
        $tokenFacade->expiresAt     = $newAccessToken->getExpiredAt();
        $tokenFacade->refreshToken = $newAccessToken->getRefreshCode();

        return $tokenFacade;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'refresh_token';
    }
}
