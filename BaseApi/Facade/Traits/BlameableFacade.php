<?php

namespace OpenOrchestra\BaseApi\Facade\Traits;

/**
 * Class BlameableFacade
 */
trait BlameableFacade
{
    /**
     * @Serializer\Type("string")
     */
    public $createdBy;

    /**
     * @Serializer\Type("string")
     */
    public $updatedBy;
}
