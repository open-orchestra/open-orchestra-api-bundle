<?php

namespace OpenOrchestra\BaseApiBundle\Tests\EventSubscriber;

use OpenOrchestra\BaseApi\EventSubscriber\SerializerSubscriber;
use Phake;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Test SerializerSubscriberTest
 */
class SerializerSubscriberTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var SerializerSubscriber
     */
    protected $subscriber;

    protected $request;
    protected $resolver;
    protected $serializer;
    protected $annotationReader;

    /**
     * Set up the test
     */
    public function setUp()
    {
        $this->resolver = Phake::mock('Symfony\Component\HttpKernel\Controller\ControllerResolverInterface');
        $this->serializer = Phake::mock('JMS\Serializer\SerializerInterface');
        $this->annotationReader = Phake::mock('Doctrine\Common\Annotations\Reader');

        $this->request = Phake::mock('Symfony\Component\HttpFoundation\Request');
        Phake::when($this->request)->get('_route')->thenReturn('open_orchestra_api');

        $this->subscriber = new SerializerSubscriber($this->serializer, $this->annotationReader, $this->resolver);
    }

    /**
     * Test instance
     */
    public function testInstance()
    {
        $this->assertInstanceOf('Symfony\Component\EventDispatcher\EventSubscriberInterface', $this->subscriber);
    }

    /**
     * Test event subscribed and method callable
     */
    public function testEventSubscribedAndMethodCallable()
    {
        $this->assertArrayHasKey(KernelEvents::VIEW, $this->subscriber->getSubscribedEvents());
        $this->assertTrue(method_exists($this->subscriber, 'onKernelViewSerialize'));
    }

    /**
     * @param string $format
     * @param string $responseContentType
     *
     * @param $classAnnotation
     * @dataProvider provideFormatAndResponseType
     */
    public function testOnKernelViewSerialize($format, $responseContentType, $classAnnotation, $controllerResult, $expectedStatusCode)
    {
        Phake::when($this->resolver)->getController(Phake::anyParameters())->thenReturn(array('\DateTime', 'add'));
        Phake::when($this->annotationReader)->getMethodAnnotation(Phake::anyParameters())->thenReturn(!$classAnnotation);
        Phake::when($this->annotationReader)->getClassAnnotation(Phake::anyParameters())->thenReturn($classAnnotation);
        Phake::when($this->request)->get('_format', 'json')->thenReturn($format);

        $event = new GetResponseForControllerResultEvent(
            Phake::mock('Symfony\Component\HttpKernel\HttpKernelInterface'),
            $this->request,
            HttpKernelInterface::MASTER_REQUEST,
            $controllerResult
        );

        $this->subscriber->onKernelViewSerialize($event);

        /** @var Response $response */
        $response = $event->getResponse();
        $this->assertInstanceOf('Symfony\Component\HttpFoundation\Response', $response);
        $this->assertEquals($expectedStatusCode, $response->getStatusCode());
        Phake::verify($this->serializer)->serialize($controllerResult, $format);
        $this->assertSame($responseContentType, $response->headers->get('content-type'));
    }

    /**
     * @return array
     */
    public function provideFormatAndResponseType()
    {
        $okCode = 200;
        $okResponse = Phake::mock('OpenOrchestra\BaseApi\Facade\FacadeInterface');
        $response = array();

        $koCode = 400;
        $koResponse = Phake::mock('Symfony\Component\Validator\ConstraintViolationListInterface');

        return array(
            array('json', 'application/json', false, $okResponse, $okCode),
            array('xml', 'text/xml', false, $okResponse, $okCode),
            array('yml', 'application/yaml', false, $okResponse, $okCode),
            array('json', 'application/json', true, $okResponse, $okCode),
            array('xml', 'text/xml', true, $okResponse, $okCode),
            array('yml', 'application/yaml', true, $okResponse, $okCode),
            array('json', 'application/json', false, $response, $okCode),
            array('xml', 'text/xml', false, $response, $okCode),
            array('yml', 'application/yaml', false, $response, $okCode),
            array('json', 'application/json', true, $response, $okCode),
            array('xml', 'text/xml', true, $response, $okCode),
            array('yml', 'application/yaml', true, $response, $okCode),
            array('json', 'application/json', false, $koResponse, $koCode),
            array('xml', 'text/xml', false, $koResponse, $koCode),
            array('yml', 'application/yaml', false, $koResponse, $koCode),
            array('json', 'application/json', true, $koResponse, $koCode),
            array('xml', 'text/xml', true, $koResponse, $koCode),
            array('yml', 'application/yaml', true, $koResponse, $koCode),
        );
    }
}
