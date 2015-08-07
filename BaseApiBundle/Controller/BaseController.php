<?php

namespace OpenOrchestra\BaseApiBundle\Controller;

use Doctrine\Common\Inflector\Inflector;
use OpenOrchestra\BaseApi\Manager\ContentTypeGeneratorTrait;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class BaseController
 */
abstract class BaseController extends Controller
{
    use ContentTypeGeneratorTrait;
    protected $violations;

    /**
     * @param mixed      $mixed
     * @param array|null $validationGroups
     *
     * @return bool
     */
    protected function isValid($mixed, $validationGroups = null)
    {
        $this->violations = $this->get('validator')->validate($mixed, $validationGroups);

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
        $mixed = $this->get('open_orchestra_api.transformer_manager')->get($typeName)->reverseTransform($facade, $mixed);

        if ($this->isValid($mixed)) {
            $em = $this->get('object_manager');
            $em->persist($mixed);
            $em->flush();

            $this->dispatchEvent($event, new $eventClass($mixed));

            return new Response('', 200);
        }

        return new Response(
            $this->get('jms_serializer')->serialize($this->getViolations(), $format),
            400,
            array('content-type' => $this->generateContentType($format))
        );
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
