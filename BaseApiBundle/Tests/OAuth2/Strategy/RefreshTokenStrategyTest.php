<?php

namespace OpenOrchestra\BaseApiBundle\Tests\OAuth2\Strategy;

use OpenOrchestra\BaseApi\OAuth2\Strategy\RefreshTokenStrategy;
use Phake;

/**
 * Test RefreshTokenStrategyTest
 */
class RefreshTokenStrategyTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var RefreshTokenStrategy
     */
    protected $strategy;

    protected $request;
    protected $validator;
    protected $serializer;
    protected $accesTokenManager;
    protected $apiClientRepository;
    protected $accessTokenRepository;

    /**
     * Set up the test
     */
    public function setUp()
    {
        $this->validator = Phake::mock('Symfony\Component\Validator\Validator\ValidatorInterface');
        $this->serializer = Phake::mock('JMS\Serializer\Serializer');
        $this->accessTokenRepository = Phake::mock('OpenOrchestra\BaseApi\Repository\AccessTokenRepositoryInterface');
        $this->apiClientRepository = Phake::mock('OpenOrchestra\BaseApi\Repository\ApiClientRepositoryInterface');
        $this->accesTokenManager = Phake::mock('OpenOrchestra\BaseApi\Manager\AccessTokenManager');
        $this->request = Phake::mock('Symfony\Component\HttpFoundation\Request');

        $this->strategy = new RefreshTokenStrategy(
            $this->apiClientRepository,
            $this->serializer,
            $this->validator,
            $this->accesTokenManager,
            $this->accessTokenRepository
        );
    }

    /**
     * Test instance
     */
    public function testInstance()
    {
        $this->assertInstanceOf('OpenOrchestra\BaseApi\OAuth2\Strategy\StrategyInterface', $this->strategy);
    }

    /**
     * Test name
     */
    public function testGetName()
    {
        $this->assertSame('refresh_token', $this->strategy->getName());
    }

    /**
     * @param bool        $support
     * @param string|null $user
     * @param string|null $password
     * @param string|null $grantType
     * @param string|null $refreshToken
     *
     * @dataProvider provideSupportAndRequestParameters
     */
    public function testSupport($support, $user, $password, $grantType, $refreshToken)
    {
        Phake::when($this->request)->getUser()->thenReturn($user);
        Phake::when($this->request)->getPassword()->thenReturn($password);
        Phake::when($this->request)->get('grant_type')->thenReturn($grantType);
        Phake::when($this->request)->get('refresh_token')->thenReturn($refreshToken);

        $this->assertSame($support, $this->strategy->supportRequestToken($this->request));
    }

    /**
     * @return array
     */
    public function provideSupportAndRequestParameters()
    {
        return array(
            array(false, null, null, null, null),
            array(true, 'user', 'password', 'refresh_token', 'token'),
            array(false, null, 'password', 'refresh_token', 'token'),
            array(false, null, null, 'refresh_token', 'token'),
            array(false, 'user', 'password', 'client_credentials', 'token'),
            array(false, 'user', 'password', 'refresh_token', null),
        );
    }
}
