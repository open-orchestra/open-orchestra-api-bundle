<?php

namespace OpenOrchestra\BaseApiBundle\Controller;

use Doctrine\Common\Inflector\Inflector;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use OpenOrchestra\ModelInterface\Model\StatusableInterface;

/**
 * Class BaseController
 */
abstract class BaseController extends Controller
{
    protected $violations;

    /**
     * @param mixed      $mixed
     * @param array|null $validationGroups
     *
     * @return bool
     */
    protected function isValid($mixed, $validationGroups = null)
    {
        $this->violations = $this->get('validator')->validate($mixed, null, $validationGroups);

        return 0 === count($this->getViolations());
    }

    /**
     * @return mixed
     */
    protected function getViolations()
    {
        return $this->violations;
    }

    /**
     * @param Request $request
     * @param string $id
     * @param string $type
     * @param string $event
     * @param string $eventClass
     *
     * @return Response
     */
    protected function reverseTransform(Request $request, $id, $type, $event, $eventClass)
    {
        $facadeName = Inflector::classify($type) . 'Facade';
        $typeName = Inflector::tableize($type);
        $format = $request->get('_format', 'json');
        $facade = $this->get('jms_serializer')->deserialize(
            $request->getContent(),
            'OpenOrchestra\ApiBundle\Facade\\' . $facadeName,
            $format
        );

        $mixed = $this->get('open_orchestra_model.repository.' . $typeName)->find($id);
        $oldStatus = null;
        if ($mixed instanceof StatusableInterface) {
            $oldStatus = $mixed->getStatus();
        }
        $mixed = $this->get('open_orchestra_api.transformer_manager')->get($typeName)->reverseTransform($facade, $mixed);

        if ($this->isValid($mixed)) {
            $em = $this->get('object_manager');
            $em->persist($mixed);
            $em->flush();

            if (in_array('OpenOrchestra\ModelInterface\Event\EventTrait\EventStatusableInterface', class_implements($eventClass))) {
                $this->dispatchEvent($event, new $eventClass($mixed, $oldStatus));

                return array();
            }
            $this->dispatchEvent($event, new $eventClass($mixed));

            return array();
        }

        return $this->getViolations();
    }

    /**
     * @param string $eventName
     * @param Event  $event
     */
    protected function dispatchEvent($eventName, $event)
    {
        $this->get('event_dispatcher')->dispatch($eventName, $event);
    }
}
