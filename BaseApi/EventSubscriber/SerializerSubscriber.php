<?php

namespace OpenOrchestra\BaseApi\EventSubscriber;

use Doctrine\Common\Annotations\Reader;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpKernel\Controller\ControllerResolverInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Class SerializerSubscriber
 */
class SerializerSubscriber extends AbstractSubscriber implements EventSubscriberInterface
{
    protected $serializer;

    /**
     * @param SerializerInterface         $serializer
     * @param Reader                      $annotationReader
     * @param ControllerResolverInterface $resolver
     */
    public function __construct(SerializerInterface $serializer, Reader $annotationReader, ControllerResolverInterface $resolver)
    {
        parent::__construct($annotationReader, $resolver);
        $this->serializer = $serializer;
    }

    /**
     * Serialize Action response with annotation @Serialize
     *
     * @param FilterResponseEvent|GetResponseForControllerResultEvent $event
     */
    public function onKernelViewSerialize(GetResponseForControllerResultEvent $event)
    {
        if (!$this->isEventEligible($event)) {
            return;
        }

        $annot = $this->extractAnnotation($event, 'OpenOrchestra\BaseApiBundle\Controller\Annotation\Serialize');

        if (!$annot) {
            return;
        }

        $format = $event->getRequest()->get('_format', 'json');
        $event->setResponse(
            new Response(
                $this->serializer->serialize(
                    $event->getControllerResult(),
                    $format),
                200,
                array('content-type' => $this->generateContentType($format))));
    }

    /**
     * @{inherit}
     *
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::VIEW => 'onKernelViewSerialize',
        );
    }

    /**
     * @param string $format
     *
     * @return string
     */
    protected function generateContentType($format)
    {
        switch ($format) {
            case 'json':
                return 'application/json';
            case 'xml' :
                return 'text/xml';
            case 'yml':
                return 'application/yaml';
            default :
                return 'text/html';
        }
    }
}
