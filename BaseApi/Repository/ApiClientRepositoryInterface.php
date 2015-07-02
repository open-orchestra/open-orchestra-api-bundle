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

    /**
     * @param array|null $descriptionEntity
     * @param array|null $columns
     * @param string|null $search
     * @param array|null $order
     * @param int|null $skip
     * @param int|null $limit
     *
     * @return array
     */
    public function findForPaginateAndSearch($descriptionEntity = null, $columns = null, $search = null, $order = null, $skip = null, $limit = null);

    /**
     * @return int
     */
    public function count();

    /**
     * @param array|null $columns
     * @param array|null $descriptionEntity
     * @param array|null $search
     *
     * @return int
     */
    public function countWithSearchFilter($descriptionEntity = null, $columns = null, $search = null);
}
