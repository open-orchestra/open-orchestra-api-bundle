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
        $developerMessage   = self::DEVELOPER_MESSAGE;
        $humanMessage       = self::HUMAN_MESSAGE;
        $statusCode         = self::STATUS_CODE;
        $errorCode          = self::ERROR_CODE;

        parent::__construct($statusCode, $errorCode, $developerMessage, $humanMessage);
    }
}
