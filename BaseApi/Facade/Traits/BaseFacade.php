<?php

namespace OpenOrchestra\BaseApi\Facade\Traits;

/**
 * Class BaseFacade
 */
trait BaseFacade
{
    /**
     * @\JMS\Serializer\Annotation\Type("string")
     */
    public $id;

    /**
     * @\JMS\Serializer\Annotation\XmlMap(inline=false, entry="right", keyAttribute="location")
     * @\JMS\Serializer\Annotation\Type("array<string,bool>")
     */
    protected $rights = array();

    /**
     * @\JMS\Serializer\Annotation\XmlMap(inline=false, entry="link", keyAttribute="location")
     * @\JMS\Serializer\Annotation\Type("array<string,string>")
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
