<?php

namespace OpenOrchestra\BaseApi\Security\Authentication\Token;

use OpenOrchestra\BaseApi\Model\ApiClientInterface;
use OpenOrchestra\BaseApi\Model\TokenInterface;
use Symfony\Component\Security\Core\Authentication\Token\AbstractToken;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class OAuth2Token
 */
class OAuth2Token extends AbstractToken
{
    protected $accessToken;

    /**
     * @param mixed $accessToken
     */
    public function setAccessToken($accessToken)
    {
        $this->accessToken = $accessToken;
    }

    /**
     * @return mixed
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }

    /**
     * @param string $accessToken
     *
     * @return OAuth2Token
     */
    public static function create($accessToken)
    {
        $token = new self();
        $token->setAccessToken($accessToken);

        return $token;
    }

    /**
     * @param TokenInterface $accessTokenEntity
     *
     * @return OAuth2Token
     */
    public static function createFromAccessTokenEntity(TokenInterface $accessTokenEntity)
    {
        $roles[] = 'ROLE_USER';
        if (($client = $accessTokenEntity->getClient()) instanceof ApiClientInterface ) {
            $roles = array_merge($roles, $client->getRoles());
        }

        if ($user = $accessTokenEntity->getUser()) {
            if ($user instanceof UserInterface) {
                $roles[] = 'ROLE_REAL_USER';
            }

            $roles = array_merge($roles, $user->getRoles());
        }
        $roles = array_unique($roles);

        $token = new self($roles);

        if ($user) {
            $token->setUser($user);
        }

        $token->setAuthenticated(true);
        $token->setAccessToken($accessTokenEntity->getCode());

        return $token;
    }

    /**
     * Returns the user credentials.
     *
     * @return mixed The user credentials
     */
    public function getCredentials()
    {
        return '';
    }
}
