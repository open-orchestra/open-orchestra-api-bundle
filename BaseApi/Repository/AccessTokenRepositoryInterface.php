<?php

namespace OpenOrchestra\BaseApi\Repository;

use Symfony\Component\Security\Core\User\UserInterface;
use OpenOrchestra\BaseApi\Model\ApiClientInterface;
use OpenOrchestra\BaseApi\Model\TokenInterface;

/**
 * Class AccessTokenRepositoryInterface
 */
Interface AccessTokenRepositoryInterface
{
    /**
     * @param ApiClientInterface $client
     *
     * @return TokenInterface
     */
    public function findOneByClientWithoutUser(ApiClientInterface $client);

    /**
     * @param string $token
     *
     * @return TokenInterface
     */
    public function findOneByCode($token);

    /**
     * @param TokenInterface $accessToken
     *
     * @deprecated use the AccessTokenManager instead, will be removed in 0.2.7
     */
    public function save(TokenInterface $accessToken);

    /**
     * @param ApiClientInterface $client
     * @param UserInterface      $user
     *
     * @deprecated use the AccessTokenManager instead, will be removed in 0.2.7
     */
    public function revokeNonUsedAccessToken(ApiClientInterface $client, UserInterface $user = null);
}
