<?php
namespace OpenOrchestra\BaseApi\Repository;

use OpenOrchestra\BaseApi\Model\ApiClientInterface;
use OpenOrchestra\BaseApi\Model\TokenInterface;

/**
 * Class AccessTokenRepository
 */
interface AccessTokenRepositoryInterface
{
    /**
     * @param ApiClientInterface $client
     *
     * @return TokenInterface
     */
    public function findOneByClientWithoutUser(ApiClientInterface $client);

    /**
     * @param ApiClientInterface $client
     * @param string             $refreshToken
     *
     * @return TokenInterface
     */
    public function findOneByClientAndRefreshToken(ApiClientInterface $client, $refreshToken);

    /**
     * @param string $token
     *
     * @return TokenInterface
     */
    public function findOneByCode($token);

    /**
     * @param array $criteria
     *
     * @return array
     */
    public function findBy(array $criteria);
}
