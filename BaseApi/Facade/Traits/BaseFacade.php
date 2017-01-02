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
     * @SerializerXmlMap(inline=false, entry="right", keyAttribute="location")
     * @Serializer\Type("array<string,boolean>")
     */
    protected $rights = array();

    /**
     * @Serializer\XmlMap(inline=false, entry="link", keyAttribute="location")
     * @Serializer\Type("array<string,string>")
     *
     * @deprecated To be removed in 2.0
     */
    protected $links = array();

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

    /**
     * @param array $links
     *
     * @deprecated To be removed in 2.0
     */
    public function setLinks($links)
    {
        $this->links = $links;
    }

    /**
     * @param string $name
     * @param string $link
     *
     * @deprecated To be removed in 2.0
     */
    public function addLink($name, $link)
    {
        $this->links[$name] = $link;
    }

    /**
     * @return array
     *
     * @deprecated To be removed in 2.0
     */
    public function getLinks()
    {
        return $this->links;
    }
}
