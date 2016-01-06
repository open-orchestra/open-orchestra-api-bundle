<?php

namespace OpenOrchestra\BaseApi\Tests\OAuth2\Strategy;

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

    /**
     * @param string     $exception
     * @param mixed|null $client
     *
     * @throws \OpenOrchestra\BaseApi\Exceptions\HttpException\TokenBlockedHttpException
     * @throws \OpenOrchestra\BaseApi\Exceptions\HttpException\TokenNotFoundHttpException
     *
     * @dataProvider provideExceptionAndClient
     */
    public function testRequestTokenWithDifferentClientException($exception, $client = null)
    {
        Phake::when($this->apiClientRepository)->findOneByKeyAndSecret(Phake::anyParameters())->thenReturn($client);
        Phake::when($this->request)->getUser()->thenReturn('user');
        Phake::when($this->request)->getPassword()->thenReturn('password');

        $this->setExpectedException($exception);
        $this->strategy->requestToken($this->request);
    }

    /**
     * @return array
     */
    public function provideExceptionAndClient()
    {
        $blockedClient = Phake::mock('OpenOrchestra\BaseApi\Model\ApiClientInterface');
        Phake::when($blockedClient)->isBlocked()->thenReturn(true);

        $untrustedClient = Phake::mock('OpenOrchestra\BaseApi\Model\ApiClientInterface');
        Phake::when($untrustedClient)->isBlocked()->thenReturn(false);
        Phake::when($untrustedClient)->isTrusted()->thenReturn(false);

        return array(
            array('OpenOrchestra\BaseApi\Exceptions\HttpException\ClientNonTrustedHttpException', $untrustedClient),
            array('OpenOrchestra\BaseApi\Exceptions\HttpException\ClientBlockedHttpException', $blockedClient),
            array('OpenOrchestra\BaseApi\Exceptions\HttpException\BadClientCredentialsHttpException', Phake::mock('stdClass')),
            array('OpenOrchestra\BaseApi\Exceptions\HttpException\BadClientCredentialsHttpException'),
        );
    }

    /**
     * @param string     $exception
     * @param mixed|null $token
     *
     * @throws \OpenOrchestra\BaseApi\Exceptions\HttpException\TokenBlockedHttpException
     * @throws \OpenOrchestra\BaseApi\Exceptions\HttpException\TokenNotFoundHttpException
     *
     * @dataProvider provideExceptionAndToken
     */
    public function testRequestTokenWithWrongToken($exception, $token = null)
    {
        $this->generateClient();

        Phake::when($this->request)->get('refresh_token')->thenReturn('foo');

        Phake::when($this->accessTokenRepository)->findOneByClientAndRefreshToken(Phake::anyParameters())->thenReturn($token);

        $this->setExpectedException($exception);
        $this->strategy->requestToken($this->request);
    }

    /**
     * @return array
     */
    public function provideExceptionAndToken()
    {
        $token = Phake::mock('OpenOrchestra\BaseApi\Model\TokenInterface');
        Phake::when($token)->isBlocked()->thenReturn(true);

        return array(
            array('OpenOrchestra\BaseApi\Exceptions\HttpException\TokenBlockedHttpException', $token),
            array('OpenOrchestra\BaseApi\Exceptions\HttpException\TokenNotFoundHttpException', Phake::mock('stdClass')),
            array('OpenOrchestra\BaseApi\Exceptions\HttpException\TokenNotFoundHttpException'),
        );
    }

    /**
     * Test token with violations
     *
     * @throws \OpenOrchestra\BaseApi\Exceptions\HttpException\TokenBlockedHttpException
     * @throws \OpenOrchestra\BaseApi\Exceptions\HttpException\TokenNotFoundHttpException
     */
    public function testRequestTokenWithViolation()
    {
        $this->generateClient();
        $this->generateToken();

        $constraintList = Phake::mock('Symfony\Component\Validator\ConstraintViolationListInterface');
        $newAccessToken = Phake::mock('OpenOrchestra\BaseApi\Model\TokenInterface');
        Phake::when($this->accesTokenManager)->create(Phake::anyParameters())->thenReturn($newAccessToken);
        Phake::when($newAccessToken)->isValid(Phake::anyParameters())->thenReturn(false);
        Phake::when($newAccessToken)->getViolations()->thenReturn($constraintList);

        $this->assertSame($constraintList, $this->strategy->requestToken($this->request));
    }

    public function testRequestToken()
    {
        $this->generateClient();
        $this->generateToken();

        $newAccessToken = Phake::mock('OpenOrchestra\BaseApi\Model\TokenInterface');
        Phake::when($this->accesTokenManager)->create(Phake::anyParameters())->thenReturn($newAccessToken);
        Phake::when($newAccessToken)->isValid(Phake::anyParameters())->thenReturn(true);

        $facade = $this->strategy->requestToken($this->request);

        Phake::verify($this->accesTokenManager)->save($newAccessToken);
        $this->assertInstanceOf('OpenOrchestra\BaseApi\Facade\FacadeInterface', $facade);
    }

    /**
     * @return mixed
     */
    protected function generateClient()
    {
        $client = Phake::mock('OpenOrchestra\BaseApi\Model\ApiClientInterface');
        Phake::when($client)->isBlocked()->thenReturn(false);
        Phake::when($client)->isTrusted()->thenReturn(true);

        Phake::when($this->apiClientRepository)->findOneByKeyAndSecret(Phake::anyParameters())->thenReturn($client);

        return $client;
    }

    /**
     * @return mixed
     */
    protected function generateToken()
    {
        $token = Phake::mock('OpenOrchestra\BaseApi\Model\TokenInterface');
        Phake::when($token)->isBlocked()->thenReturn(false);

        Phake::when($this->accessTokenRepository)->findOneByClientAndRefreshToken(Phake::anyParameters())->thenReturn($token);

        return $token;
    }
}
