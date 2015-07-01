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
        $developerMessage   = self::DEVELOPER_MESSAGE;
        $humanMessage       = self::HUMAN_MESSAGE;
        $statusCode         = self::STATUS_CODE;
        $errorCode          = self::ERROR_CODE;

        parent::__construct($statusCode, $errorCode, $developerMessage, $humanMessage);
    }
}
