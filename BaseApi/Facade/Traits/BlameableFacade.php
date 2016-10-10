<?php

namespace OpenOrchestra\BaseApi\Facade\Traits;

/**
 * Class BlameableFacade
 */
trait BlameableFacade
{
    /**
     * @\JMS\Serializer\Annotation\Type("string")
     */
    public $createdBy;

    /**
     * @\JMS\Serializer\Annotation\Type("string")
     */
    public $updatedBy;
}
