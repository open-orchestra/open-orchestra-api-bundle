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

    /**
     * @param TokenInterface $accessToken
     *
     * @deprecated use the AccessTokenManager instead, will be removed in 0.2.7
     */
    public function save(TokenInterface $accessToken)
    {
        $this->revokeNonUsedAccessToken($accessToken->getClient(), $accessToken->getUser());

        $dm = $this->getDocumentManager();
        $dm->persist($accessToken);
        $dm->flush($accessToken);
    }

    /**
     * @param ApiClientInterface $client
     * @param UserInterface      $user
     *
     * @deprecated use the AccessTokenManager instead, will be removed in 0.2.7
     */
    public function revokeNonUsedAccessToken(ApiClientInterface $client, UserInterface $user = null)
    {
        $searchParams = array(
            'client'  => $client->getId(),
            'blocked' => false
        );
        $searchParams['user'] = null;
        $searchParams['customer'] = null;
        if ($user instanceof UserInterface) {
            $searchParams['user'] = $user->getId();
        }

        $accessTokens = $this->findBy($searchParams);

        $dm = $this->getDocumentManager();

        /** @var TokenInterface $accessToken */
        foreach ($accessTokens as $accessToken) {
            $accessToken->block();
            $dm->persist($accessToken);
        }
        $dm->flush();
    }
}
