<?php

namespace OpenOrchestra\BaseApi\Exceptions\HttpException;

/**
 * Class TokenBlockedHttpException
 */
class TokenBlockedHttpException extends ApiException
{
    const DEVELOPER_MESSAGE  = 'token.blocked';
    const HUMAN_MESSAGE      = 'api.exception.token_blocked';
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
