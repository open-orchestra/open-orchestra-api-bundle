<?php

namespace OpenOrchestra\BaseApi\Exceptions\HttpException;

/**
 * Class TokenExpiredHttpException
 */
class TokenExpiredHttpException extends ApiException
{
    const DEVELOPER_MESSAGE  = 'token.expired';
    const HUMAN_MESSAGE      = 'api.exception.token_expired';
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
