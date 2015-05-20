<?php

namespace OpenOrchestra\BaseApi\Facade\OAuth2;

use JMS\Serializer\Annotation as Serializer;
use OpenOrchestra\BaseApi\Facade\AbstractFacade;

/**
 * Class AccessTokenFacade
 */
class AccessTokenFacade extends AbstractFacade
{
    /**
     * @Serializer\Type("string")
     */
    public $accessToken;

    /**
     * @Serializer\Type("DateTime<'d-m-Y H:i:s'>")
     *
     * @deprecated use expiresAt instead, will be removed in 0.2.4
     */
    public $expiresIn;

    /**
     * @Serializer\Type("DateTime<'d-m-Y H:i:s'>")
     */
    public $expiresAt;
}
