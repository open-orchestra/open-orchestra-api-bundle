<?php

namespace OpenOrchestra\BaseApi\Tests\Manager;

use OpenOrchestra\BaseApi\Manager\AccessTokenManager;
use OpenOrchestra\BaseApi\Model\ApiClientInterface;
use OpenOrchestra\BaseApi\Model\TokenInterface;
use Phake;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class AccessTokenManagerTest
 */
class AccessTokenManagerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var AccessTokenManager
     */
    protected $manager;

    protected $accessTokenRepository;
    protected $objectManager;
    protected $tokenClass;

    /**
     * Set up the test
     */
    public function setUp()
    {
        $this->accessTokenRepository = Phake::mock('OpenOrchestra\BaseApi\Repository\AccessTokenRepositoryInterface');
        $this->objectManager = Phake::mock('Doctrine\Common\Persistence\ObjectManager');
        $this->tokenClass = 'OpenOrchestra\BaseApi\Tests\Manager\TestAccessToken';

        $this->manager = new AccessTokenManager($this->accessTokenRepository, $this->objectManager, $this->tokenClass, '+1month');
    }

    /**
     * Test create
     */
    public function testCreate()
    {
        $client = Phake::mock('OpenOrchestra\BaseApi\Model\ApiClientInterface');
        $user = Phake::mock('Symfony\Component\Security\Core\User\UserInterface');

        $token = $this->manager->create($client, $user);

        $this->assertInstanceOf('OpenOrchestra\BaseApi\Model\TokenInterface', $token);
        $this->assertSame($client, $token->getClient());
        $this->assertSame($user, $token->getUser());
    }

    /**
     * Test with expiration date
     */
    public function testCreateWithExpirationDate()
    {
        $client = Phake::mock('OpenOrchestra\BaseApi\Model\ApiClientInterface');
        $user = Phake::mock('Symfony\Component\Security\Core\User\UserInterface');

        $token = $this->manager->createWithExpirationDate($client, $user);

        $this->assertInstanceOf('OpenOrchestra\BaseApi\Model\TokenInterface', $token);
        $this->assertSame($client, $token->getClient());
        $this->assertSame($user, $token->getUser());
        $this->assertGreaterThan(new \DateTime('+27days'), $token->getExpiredAt());
    }

    /**
     * Test save with no revocation
     */
    public function testSaveWithNoRevocation()
    {
        $token = Phake::mock('OpenOrchestra\BaseApi\Model\TokenInterface');

        $this->manager->save($token);

        Phake::verify($this->objectManager)->persist($token);
        Phake::verify($this->objectManager)->flush();
    }

    /**
     * @param string      $clientId
     * @param mixed       $user
     * @param string|null $userId
     *
     * @dataProvider provideClientIdAndUser
     */
    public function testSaveWithRevocation($clientId, $user, $userId = null)
    {
        $existingToken = Phake::mock('OpenOrchestra\BaseApi\Model\TokenInterface');
        Phake::when($this->accessTokenRepository)->findBy(Phake::anyParameters())->thenReturn(array($existingToken, $existingToken));

        $client = Phake::mock('OpenOrchestra\BaseApi\Model\ApiClientInterface');
        Phake::when($client)->getId()->thenReturn($clientId);

        $token = Phake::mock('OpenOrchestra\BaseApi\Model\TokenInterface');
        Phake::when($token)->getClient()->thenReturn($client);
        Phake::when($token)->getUser()->thenReturn($user);

        $this->manager->save($token, true);

        Phake::verify($existingToken, Phake::times(2))->block();
        Phake::verify($this->objectManager, Phake::times(2))->persist($existingToken);
        Phake::verify($this->objectManager)->persist($token);
        Phake::verify($this->objectManager, Phake::times(2))->flush();
        Phake::verify($this->accessTokenRepository)->findBy(array(
            'user.id' => $userId,
            'blocked' => false,
            'client.id' => $clientId,
        ));
    }

    /**
     * @return array
     */
    public function provideClientIdAndUser()
    {
        $nonIdUser = Phake::mock('Symfony\Component\Security\Core\User\UserInterface');

        $userId = 'baz';
        $idUser = Phake::mock('OpenOrchestra\BaseApi\Tests\Manager\TestUserWithGetId');
        Phake::when($idUser)->getId()->thenReturn($userId);

        return array(
            array('foo', $nonIdUser),
            array('bar', $idUser, $userId),
        );
    }
}

/**
 * Interface TestUserWithGetId
 */
interface TestUserWithGetId extends  UserInterface
{
    public function getId();
}

/**
 * Class TestAccessToken
 */
class TestAccessToken implements TokenInterface
{
    protected $client;
    protected $user;
    protected $expiredAt;

    /**
     * @param bool $blocked
     */
    public function setBlocked($blocked)
    {
    }

    /**
     * @return bool
     */
    public function isBlocked()
    {
    }

    /**
     * Block a client
     */
    public function block()
    {
    }

    /**
     * Unblock a client
     */
    public function unblock()
    {
    }

    /**
     * @param \DateTime $expiredAt
     */
    public function setExpiredAt(\DateTime $expiredAt)
    {
        $this->expiredAt = $expiredAt;
    }

    /**
     * @return \DateTime
     */
    public function getExpiredAt()
    {
        return $this->expiredAt;
    }

    /**
     * @return boolean
     */
    public function isExpired()
    {
    }

    /**
     * @return string
     */
    public function getCode()
    {
    }

    /**
     * @param string $code
     */
    public function setCode($code)
    {
    }

    /**
     * @return string
     */
    public function getRefreshCode()
    {
    }

    /**
     * @param string $refreshCode
     */
    public function setRefreshCode($refreshCode)
    {
    }

    /**
     * @return ApiClientInterface
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @param ApiClientInterface $client
     */
    public function setClient(ApiClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @return UserInterface
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param UserInterface $user
     */
    public function setUser(UserInterface $user)
    {
        $this->user = $user;
    }

    /**
     * @param ApiClientInterface $client
     * @param UserInterface $user
     *
     * @return TokenInterface
     */
    public static function create(ApiClientInterface $client, UserInterface $user = null)
    {
        $accessToken = new self();
        $accessToken->setUser($user);
        $accessToken->setClient($client);

        return $accessToken;
    }

    /**
     * @param ValidatorInterface $validator
     *
     * @return boolean
     */
    public function isValid(ValidatorInterface $validator)
    {
    }

    /**
     * @return ConstraintViolationListInterface
     */
    public function getViolations()
    {
    }
}
