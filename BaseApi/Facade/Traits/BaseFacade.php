<?php

namespace OpenOrchestra\BaseApi\Facade\Traits;

use JMS\Serializer\Annotation as Serializer;

/**
 * Class BaseFacade
 */
trait BaseFacade
{
    /**
     * @Serializer\Type("string")
     */
    public $id;

    /**
     * @Serializer\XmlMap(inline=false, entry="right", keyAttribute="location")
     * @Serializer\Type("array<string,boolean>")
     */
    protected $rights = array();

    /**
     * @param array $rights
     */
    public function setRights($rights)
    {
        $this->rights = $rights;
    }

    /**
     * @param string $name
     * @param bool   $right
     */
    public function addRight($name, $right)
    {
        $this->rights[$name] = $right;
    }

    /**
     * @return array
     */
    public function getRights()
    {
        return $this->rights;
    }
}
