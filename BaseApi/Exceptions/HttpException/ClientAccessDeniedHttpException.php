<?php

namespace OpenOrchestra\BaseApi\Exceptions\HttpException;

/**
 * Class ClientAccessDeniedHttpException
 */
class ClientAccessDeniedHttpException extends ApiException
{
    const DEVELOPER_MESSAGE  = 'client.access_denied';
    const HUMAN_MESSAGE      = 'api.exception.client_access_denied';
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
