<?php

namespace OpenOrchestra\BaseApi\EventSubscriber;

use Doctrine\Common\Annotations\Reader;
use OpenOrchestra\BaseApi\Context\GroupContext;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Controller\ControllerResolverInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Class GroupContextSubscriber
 */
class GroupContextSubscriber extends AbstractSubscriber implements EventSubscriberInterface
{
    protected $groupContext;

    /**
     * @param GroupContext                $groupContext
     * @param Reader                      $annotationReader
     * @param ControllerResolverInterface $resolver
     */
    public function __construct(GroupContext $groupContext, Reader $annotationReader, ControllerResolverInterface $resolver)
    {
        parent::__construct($annotationReader, $resolver);
        $this->groupContext = $groupContext;
    }

    /**
     * @param GetResponseEvent $event
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        if (!$this->eventElligible($event)) {
            return;
        }

        $annot = $this->extractAnnotation($event, 'OpenOrchestra\BaseApiBundle\Controller\Annotation\Groups');

        if (!$annot) {
            return;
        }

        $this->groupContext->setGroups($annot->groups);
    }

    /**
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::REQUEST => 'onKernelRequest',
        );
    }
}
