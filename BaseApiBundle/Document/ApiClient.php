<?php

namespace OpenOrchestra\BaseApiBundle\Document;

use OpenOrchestra\BaseApi\Model\ApiClientInterface;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class ApiClient
 *
 * @ODM\Document(
 *   collection="api_client",
 *   repositoryClass="OpenOrchestra\BaseApiBundle\Repository\ApiClientRepository"
 * )
 */
class ApiClient implements ApiClientInterface
{
    use Blockable;

    /**
     * @ODM\Id()
     */
    protected $id;

    /**
     * @ODM\Field(type="string")
     */
    protected $key;

    /**
     * @ODM\Field(type="string")
     */
    protected $secret;

    /**
     * @Assert\NotBlank()
     * @ODM\Field(type="string")
     */
    protected $name;

    /**
     * @Assert\Type(type="bool")
     * @ODM\Field(type="boolean")
     */
    protected $trusted;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->key    = $this->generateId();
        $this->secret = $this->generateId();
    }

    /**
     * Generate an unique Id
     *
     * @return string
     */
    public function generateId()
    {
        $data = unpack('H*', openssl_random_pseudo_bytes(32));

        return array_pop($data);
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param string $key
     */
    public function setKey($key)
    {
        $this->key = $key;
    }

    /**
     * @return string
     */
    public function getSecret()
    {
        return $this->secret;
    }

    /**
     * @param string $secret
     */
    public function setSecret($secret)
    {
        $this->secret = $secret;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return boolean
     */
    public function isTrusted()
    {
        return $this->trusted;
    }

    /**
     * @param bool $trusted
     */
    public function setTrusted($trusted)
    {
        $this->trusted = $trusted;
    }
}
