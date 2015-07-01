<?php

namespace OpenOrchestra\BaseApiModelBundle\Repository;

use Doctrine\ODM\MongoDB\DocumentRepository;
use OpenOrchestra\BaseApi\Repository\AccessTokenRepositoryInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use OpenOrchestra\BaseApi\Model\ApiClientInterface;
use OpenOrchestra\BaseApi\Model\TokenInterface;

/**
 * Class AccessTokenRepository
 */
class AccessTokenRepository extends DocumentRepository implements AccessTokenRepositoryInterface
{
    /**
     * @param ApiClientInterface $client
     *
     * @return TokenInterface
     */
    public function findOneByClientWithoutUser(ApiClientInterface $client)
    {
        $qb = $this->createQueryBuilder();

        $qb->field('client.$id')->equals($client->getId());
        $qb->field('user')->equals(null);
        $qb->sort('createdAt', 'desc');
        $qb->limit(1);

        return $qb->getQuery()->getSingleResult();
    }

    /**
     * @param string $token
     *
     * @return TokenInterface
     */
    public function findOneByCode($token)
    {
        return $this->findOneBy(array('code' => $token));
    }
}
