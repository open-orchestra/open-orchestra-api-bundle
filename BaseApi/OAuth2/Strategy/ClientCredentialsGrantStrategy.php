<?php

namespace OpenOrchestra\BaseApi\OAuth2\Strategy;

use OpenOrchestra\BaseApi\Facade\FacadeInterface;
use OpenOrchestra\BaseApi\Facade\OAuth2\AccessTokenFacade;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use OpenOrchestra\BaseApi\Model\TokenInterface as OrchestraTokenInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * Class ClientCredentialsGrantStrategy
 */
class ClientCredentialsGrantStrategy extends AbstractStrategy
{
    /**
     * @param Request $request
     *
     * @return boolean
     */
    public function supportRequestToken(Request $request)
    {
        $clientExist = $request->getUser() && $request->getPassword();
        $oauthParams = $request->get('grant_type') === 'client_credentials';

        return $oauthParams && $clientExist;
    }

    /**
     * @param Request $request [description]
     *
     * @return ConstraintViolationListInterface|FacadeInterface
     */
    public function requestToken(Request $request)
    {
        $client = $this->getClient($request);

        /** @var TokenInterface $accessToken */
        $accessToken = $this->accessTokenRepository->findOneByClientWithoutUser($client);

        if (is_null($accessToken) || $accessToken->isBlocked() || $accessToken->isExpired()) {
            /** @var OrchestraTokenInterface $accessToken */
            $accessToken = $this->accessTokenManager->create($client);
            if (!$accessToken->isValid($this->validator)) {
                return $accessToken->getViolations();
            }

            $this->accessTokenManager->save($accessToken);
        }

        $tokenFacade = new AccessTokenFacade();
        $tokenFacade->accessToken   = $accessToken->getCode();
        $tokenFacade->expiresAt     = $accessToken->getExpiredAt();
        $tokenFacade->refreshToken = $accessToken->getRefreshCode();

        return $tokenFacade;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'client_credentials';
    }
}
