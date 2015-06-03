<?php

namespace OpenOrchestra\BaseApi\Manager;

use Doctrine\Common\Persistence\ObjectManager;
use OpenOrchestra\BaseApi\Model\ApiClientInterface;
use OpenOrchestra\BaseApi\Model\TokenInterface;
use OpenOrchestra\BaseApiBundle\Repository\AccessTokenRepository;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class AccessTokenManager
 */
class AccessTokenManager
{
    protected $accessTokenRepository;
    protected $tokenExpiration;
    protected $objectManager;
    protected $tokenClass;

    /**
     * @param AccessTokenRepository $accessTokenRepository
     * @param ObjectManager         $objectManager
     * @param string                $tokenClass
     * @param string                $tokenExpiration
     */
    public function __construct(AccessTokenRepository $accessTokenRepository, ObjectManager $objectManager, $tokenClass, $tokenExpiration)
    {
        $this->accessTokenRepository = $accessTokenRepository;
        $this->tokenExpiration = $tokenExpiration;
        $this->objectManager = $objectManager;
        $this->tokenClass = $tokenClass;
    }

    /**
     * @param TokenInterface $accessToken
     */
    public function save(TokenInterface $accessToken)
    {
        $this->revokeNonUsedAccessToken($accessToken->getClient(), $accessToken->getUser());

        $this->objectManager->persist($accessToken);
        $this->objectManager->flush($accessToken);
    }

    /**
     * @param ApiClientInterface $client
     * @param UserInterface      $user
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

        $accessTokens = $this->accessTokenRepository->findBy($searchParams);

        /** @var TokenInterface $accessToken */
        foreach ($accessTokens as $accessToken) {
            $accessToken->block();
            $this->objectManager->persist($accessToken);
        }
        $this->objectManager->flush();
    }

    /**
     * @param ApiClientInterface $client
     *
     * @return TokenInterface
     */
    public function findOneByClientWithoutUser(ApiClientInterface $client)
    {
        return $this->accessTokenRepository->findOneByClientWithoutUser($client);
    }

    /**
     * @param string $token
     *
     * @return TokenInterface
     */
    public function findOneByCode($token)
    {
        return $this->accessTokenRepository->findOneByCode($token);
    }

    /**
     * @param UserInterface      $user
     * @param ApiClientInterface $client
     *
     * @return TokenInterface
     */
    public function create(UserInterface $user = null, ApiClientInterface $client)
    {
        $tokenClass = $this->tokenClass;

        return $tokenClass::create($user, $client);
    }

    /**
     * @param UserInterface      $user
     * @param ApiClientInterface $client
     *
     * @return TokenInterface
     */
    public function createWithExpirationDate(UserInterface $user = null, ApiClientInterface $client)
    {
        $accessToken = $this->create($user, $client);
        $accessToken->setExpiredAt(new \DateTime($this->tokenExpiration));

        return $accessToken;
    }
}
