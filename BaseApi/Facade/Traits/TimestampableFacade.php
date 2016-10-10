<?php

namespace OpenOrchestra\BaseApi\Facade\Traits;

/**
 * Trait TimestampableFacade
 */
trait TimestampableFacade
{
    /**
     * @\JMS\Serializer\Annotation\Type("DateTime<'d/m/Y H:i:s'>")
     */
    public $createdAt;

    /**
     * @\JMS\Serializer\Annotation\Type("DateTime<'d/m/Y H:i:s'>")
     */
    public $updatedAt;
}
