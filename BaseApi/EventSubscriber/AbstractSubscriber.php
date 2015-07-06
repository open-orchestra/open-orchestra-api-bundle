<?php

namespace OpenOrchestra\BaseApi\EventSubscriber;

use Doctrine\Common\Annotations\Reader;
use Symfony\Component\HttpKernel\Controller\ControllerResolverInterface;
use Symfony\Component\HttpKernel\Event\KernelEvent;

/**
 * Class AbstractSubscriber
 */
abstract class AbstractSubscriber
{
    protected $resolver;
    protected $annotationReader;

    /**
     * @param Reader                      $annotationReader
     * @param ControllerResolverInterface $resolver
     */
    public function __construct(Reader $annotationReader, ControllerResolverInterface $resolver)
    {
        $this->resolver = $resolver;
        $this->annotationReader = $annotationReader;
    }

    /**
     * @param KernelEvent $event
     * @param string      $annotationClassName
     *
     * @return null|object
     */
    protected function extractAnnotation(KernelEvent $event, $annotationClassName)
    {
        $controller = $this->resolver->getController($event->getRequest());
        $reflectionClass = new \ReflectionClass($controller[0]);
        $annot = $this->annotationReader->getMethodAnnotation($reflectionClass->getMethod(
            $controller[1]),
            $annotationClassName
        );

        return $annot;
    }

    /**
     * @param KernelEvent $event
     *
     * @return bool
     */
    protected function isEventEligible(KernelEvent $event)
    {
        return $event->isMasterRequest();
    }
}
