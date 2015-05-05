<?php

namespace OpenOrchestra\BaseApiBundle\Facade\Traits;

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
