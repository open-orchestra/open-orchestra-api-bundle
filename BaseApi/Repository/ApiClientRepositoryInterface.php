<?php

namespace OpenOrchestra\BaseApi\Repository;

use OpenOrchestra\BaseApi\Model\ApiClientInterface;
use OpenOrchestra\Pagination\Configuration\FinderConfiguration;
use OpenOrchestra\Pagination\Configuration\PaginateFinderConfiguration;
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

    /**
     * @param PaginateFinderConfiguration $configuration
     *
     * @return mixed
     * @throws \Doctrine\ODM\MongoDB\MongoDBException
     */
    public function findForPaginate(PaginateFinderConfiguration $configuration);

    /**
     * @return int
     */
    public function count();

    /**
     * @param FinderConfiguration $configuration
     *
     * @return mixed
     */
    public function countWithFilter(FinderConfiguration $configuration);
}
