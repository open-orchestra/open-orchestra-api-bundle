<?php

namespace OpenOrchestra\BaseApi\OAuth2\Strategy;

use OpenOrchestra\BaseApi\Exceptions\HttpException\BadClientCredentialsHttpException;
use OpenOrchestra\BaseApi\Exceptions\HttpException\ClientBlockedHttpException;
use OpenOrchestra\BaseApi\Exceptions\HttpException\ClientNonTrustedHttpException;
use JMS\Serializer\Serializer;
use OpenOrchestra\BaseApi\Manager\AccessTokenManager;
use OpenOrchestra\BaseApi\Model\ApiClientInterface;
use OpenOrchestra\BaseApi\Repository\AccessTokenRepositoryInterface;
use OpenOrchestra\BaseApi\Repository\ApiClientRepositoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class AbstractStrategy
 */
abstract class AbstractStrategy implements StrategyInterface
{
    protected $accessTokenRepository;
    protected $apiClientRepository;
    protected $accessTokenManager;
    protected $serializer;
    protected $validator;

    /**
     * @param ApiClientRepositoryInterface   $apiClientRepository
     * @param Serializer                     $serializer
     * @param ValidatorInterface             $validator
     * @param AccessTokenManager             $accessTokenManager
     * @param AccessTokenRepositoryInterface $accessTokenRepository
     */
    public function __construct(
        ApiClientRepositoryInterface $apiClientRepository,
        Serializer $serializer,
        ValidatorInterface $validator,
        AccessTokenManager $accessTokenManager,
        AccessTokenRepositoryInterface $accessTokenRepository
    )
    {
        $this->accessTokenRepository = $accessTokenRepository;
        $this->apiClientRepository = $apiClientRepository;
        $this->accessTokenManager = $accessTokenManager;
        $this->serializer = $serializer;
        $this->validator = $validator;
    }

    /**
     * @param Request $request
     *
     * @return ApiClientInterface
     *
     * @throws BadClientCredentialsHttpException
     * @throws ClientNonTrustedHttpException
     * @throws ClientBlockedHttpException
     */
    protected function getClient(Request $request)
    {
        $client = $this->apiClientRepository->findOneByKeyAndSecret($request->getUser(), $request->getPassword());
        if (!$client instanceof ApiClientInterface) {
            throw new BadClientCredentialsHttpException();
        } elseif ($client->isBlocked()) {
            throw new ClientBlockedHttpException();
        } elseif (!$client->isTrusted()) {
            throw new ClientNonTrustedHttpException();
        }

        return $client;
    }
}
