<?php

namespace OpenOrchestra\BaseApi\Repository;

use OpenOrchestra\BaseApi\Model\ApiClientInterface;

/**
 * Class ApiClientRepository
 */
interface ApiClientRepositoryInterface
{
    /**
     * @param string $key
     * @param string $secret
     *
     * @return ApiClientInterface
     */
    public function findOneByKeyAndSecret($key, $secret);
}
