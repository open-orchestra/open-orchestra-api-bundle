<?php

namespace OpenOrchestra\BaseApi\Manager;

use Doctrine\Common\Persistence\ObjectManager;
use OpenOrchestra\BaseApi\Model\ApiClientInterface;
use OpenOrchestra\BaseApi\Model\TokenInterface;
use OpenOrchestra\BaseApi\Repository\AccessTokenRepositoryInterface;
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
     * @param AccessTokenRepositoryInterface $accessTokenRepository
     * @param ObjectManager                  $objectManager
     * @param string                         $tokenClass
     * @param string                         $tokenExpiration
     */
    public function __construct(AccessTokenRepositoryInterface $accessTokenRepository, ObjectManager $objectManager, $tokenClass, $tokenExpiration)
    {
        $this->accessTokenRepository = $accessTokenRepository;
        $this->tokenExpiration = $tokenExpiration;
        $this->objectManager = $objectManager;
        $this->tokenClass = $tokenClass;
    }

    /**
     * @param TokenInterface $accessToken
     * @param bool           $revokeNonUsedAccessToken
     */
    public function save(TokenInterface $accessToken, $revokeNonUsedAccessToken = false)
    {
        if ($revokeNonUsedAccessToken) {
            $this->revokeNonUsedAccessToken($accessToken->getClient(), $accessToken->getUser());
        }

        $this->objectManager->persist($accessToken);
        $this->objectManager->flush();
    }

    /**
     * @param ApiClientInterface $client
     * @param UserInterface      $user
     */
    protected function revokeNonUsedAccessToken(ApiClientInterface $client, UserInterface $user = null)
    {
        $searchParams = array(
            'client.id'  => $client->getId(),
            'blocked' => false
        );
        $searchParams['user.id'] = null;
        if ($user instanceof UserInterface && method_exists($user, 'getId')) {
            $searchParams['user.id'] = $user->getId();
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
     * @param UserInterface      $user
     *
     * @return TokenInterface
     */
    public function create(ApiClientInterface $client, UserInterface $user = null)
    {
        $tokenClass = $this->tokenClass;

        return $tokenClass::create($client, $user);
    }

    /**
     * @param ApiClientInterface $client
     * @param UserInterface      $user
     *
     * @return TokenInterface
     */
    public function createWithExpirationDate(ApiClientInterface $client, UserInterface $user = null)
    {
        $accessToken = $this->create($client, $user);
        $accessToken->setExpiredAt(new \DateTime($this->tokenExpiration));

        return $accessToken;
    }
}
