<?php

namespace OpenOrchestra\BaseApi\Exceptions\HttpException;

/**
 * Class UserNotFoundHttpException
 */
class UserNotFoundHttpException extends ApiException
{
    const DEVELOPER_MESSAGE  = 'user.not_found';
    const HUMAN_MESSAGE      = 'api.exception.user_not_found';
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
