<?php

namespace OpenOrchestra\BaseApi\Model;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class TokenInterface
 */
interface TokenInterface extends BlockableInterface, ExpireableInterface
{
    /**
     * @return string
     */
    public function getCode();

    /**
     * @param string $code
     */
    public function setCode($code);

    /**
     * @return ApiClientInterface
     */
    public function getClient();

    /**
     * @param ApiClientInterface $client
     */
    public function setClient(ApiClientInterface $client);

    /**
     * @return UserInterface
     */
    public function getUser();

    /**
     * @param UserInterface $user
     */
    public function setUser(UserInterface $user);

    /**
     * @param ApiClientInterface $client
     * @param UserInterface      $user
     *
     * @return TokenInterface
     */
    public static function create(ApiClientInterface $client, UserInterface $user = null);

    /**
     * @param ValidatorInterface $validator
     *
     * @return boolean
     */
    public function isValid(ValidatorInterface $validator);
}
