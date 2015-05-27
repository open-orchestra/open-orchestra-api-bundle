<?php

namespace OpenOrchestra\BaseApiBundle\Repository;

use Doctrine\ODM\MongoDB\DocumentRepository;
use OpenOrchestra\BaseApi\Model\ApiClientInterface;

/**
 * Class ApiClientRepository
 */
class ApiClientRepository extends DocumentRepository
{
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
