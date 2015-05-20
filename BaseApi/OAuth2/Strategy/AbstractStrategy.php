<?php

namespace OpenOrchestra\BaseApi\OAuth2\Strategy;

use OpenOrchestra\BaseApi\Exceptions\HttpException\BadClientCredentialsHttpException;
use OpenOrchestra\BaseApi\Exceptions\HttpException\ClientBlockedHttpException;
use OpenOrchestra\BaseApi\Exceptions\HttpException\ClientNonTrustedHttpException;
use JMS\Serializer\Serializer;
use OpenOrchestra\UserBundle\Model\ApiClientInterface;
use OpenOrchestra\UserBundle\Repository\AccessTokenRepository;
use OpenOrchestra\UserBundle\Repository\ApiClientRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class AbstractStrategy
 */
abstract class AbstractStrategy implements StrategyInterface
{
    protected $accessTokenRepository;
    protected $apiClientRepository;
    protected $tokenExpiration;
    protected $tokenClass;
    protected $serializer;
    protected $validator;

    /**
     * @param ApiClientRepository   $apiClientRepository
     * @param AccessTokenRepository $accessTokenRepository
     * @param Serializer            $serializer
     * @param ValidatorInterface    $validator
     * @param string                $tokenExpiration
     * @param string                $tokenClass
     */
    public function __construct(
        ApiClientRepository $apiClientRepository,
        AccessTokenRepository $accessTokenRepository,
        Serializer $serializer,
        ValidatorInterface $validator,
        $tokenExpiration,
        $tokenClass
    )
    {
        $this->apiClientRepository = $apiClientRepository;
        $this->accessTokenRepository = $accessTokenRepository;
        $this->tokenExpiration = $tokenExpiration;
        $this->tokenClass = $tokenClass;
        $this->serializer = $serializer;
        $this->validator = $validator;
    }

    /**
     * @param Request $request
     *
     * @return ApiClientInterface
     * @throws BadClientCredentialsHttpException
     * @throws ClientNonTrustedHttpException
     * @throws ClientBlockedHttpException
     */
    protected function getClient(Request $request)
    {
        $client = $this->apiClientRepository->findOneByKeyAndSecret($request->getUser(), $request->getPassword());
        if (!$client) {
            throw new BadClientCredentialsHttpException();
        } elseif ($client->isBlocked()) {
            throw new ClientBlockedHttpException();
        } elseif (!$client->isTrusted()) {
            throw new ClientNonTrustedHttpException();
        }

        return $client;
    }
}
