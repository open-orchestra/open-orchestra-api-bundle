<?php

namespace OpenOrchestra\BaseApi\Repository;

use OpenOrchestra\BaseApi\Model\ApiClientInterface;
use OpenOrchestra\Pagination\Configuration\PaginationRepositoryInterface;

/**
 * Class ApiClientRepository
 */
interface ApiClientRepositoryInterface extends PaginationRepositoryInterface
{
    /**
     * @param string $key
     * @param string $secret
     *
     * @return ApiClientInterface
     */
    public function findOneByKeyAndSecret($key, $secret);
}
