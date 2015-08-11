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
use Symfony\Component\Validator\ConstraintViolationListInterface;

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
        $statusCode = 200;
        $controllerResult = $event->getControllerResult();
        if ($controllerResult instanceof ConstraintViolationListInterface) {
            $statusCode = 400;
        }

        $event->setResponse(
            new Response(
                $this->generateResponseContent(
                    $controllerResult,
                    $format
                ),
                $statusCode,
                array('content-type' => $this->generateContentType($format))
            )
        );
    }

    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * @return array The event names to listen to
     *
     * @api
     */
    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::VIEW => 'onKernelViewSerialize',
        );
    }

    /**
     * Generate the content of the response
     *
     * @param mixed  $controllerResult
     * @param string $format
     *
     * @return string
     */
    protected function generateResponseContent($controllerResult, $format)
    {
        return $this->serializer->serialize(
            $controllerResult,
            $format);
    }
}
