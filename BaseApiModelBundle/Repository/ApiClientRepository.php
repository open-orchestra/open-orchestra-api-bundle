<?php

namespace OpenOrchestra\BaseApiModelBundle\Repository;

use Doctrine\ODM\MongoDB\DocumentRepository;
use OpenOrchestra\BaseApi\Model\ApiClientInterface;
use OpenOrchestra\BaseApi\Repository\ApiClientRepositoryInterface;
use OpenOrchestra\Pagination\MongoTrait\PaginateTrait;


/**
 * Class ApiClientRepository
 */
class ApiClientRepository extends DocumentRepository implements ApiClientRepositoryInterface
{
    use PaginateTrait;
    /**
     * @param string $key
     * @param string $secret
     *
     * @return ApiClientInterface
     */
    public function findOneByKeyAndSecret($key, $secret)
    {
        return $this->findOneBy(array('key' => $key, 'secret' => $secret));
    }
}
