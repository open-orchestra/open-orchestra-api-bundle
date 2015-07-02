<?php

namespace OpenOrchestra\BaseApi\Repository;

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
}
