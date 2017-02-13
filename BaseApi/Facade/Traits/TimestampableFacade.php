<?php

namespace OpenOrchestra\BaseApi\Facade\Traits;

/**
 * Trait TimestampableFacade
 */
trait TimestampableFacade
{
    /**
     * @\JMS\Serializer\Annotation\Type("DateTime")
     */
    public $createdAt;

    /**
     * @\JMS\Serializer\Annotation\Type("DateTime")
     */
    public $updatedAt;
}
