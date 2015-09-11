<?php

namespace OpenOrchestra\BaseApi\Exceptions\HttpException;

/**
 * Class AuthorizationNonSupportedHttpException
 */
class AuthorizationNonSupportedHttpException extends ApiException
{
    const DEVELOPER_MESSAGE  = 'authorization_non_supported';
    const HUMAN_MESSAGE      = 'open_orchestra_api.authorization_non_supported';
    const STATUS_CODE        = '404';
    const ERROR_CODE         = 'x';

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct(self::STATUS_CODE, self::ERROR_CODE, self::DEVELOPER_MESSAGE, self::HUMAN_MESSAGE);
    }
}
