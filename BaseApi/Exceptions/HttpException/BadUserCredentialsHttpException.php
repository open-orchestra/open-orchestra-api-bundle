<?php

namespace OpenOrchestra\BaseApi\Exceptions\HttpException;

/**
 * Class BadUserCredentialsHttpException
 */
class BadUserCredentialsHttpException extends ApiException
{
    const DEVELOPER_MESSAGE  = 'user.bad_credentials';
    const HUMAN_MESSAGE      = 'api.exception.user_bad_credentials';
    const STATUS_CODE        = '401';
    const ERROR_CODE         = 'x';

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct(self::STATUS_CODE, self::ERROR_CODE, self::DEVELOPER_MESSAGE, self::HUMAN_MESSAGE);
    }
}
