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
        parent::__construct(self::STATUS_CODE, self::ERROR_CODE, self::DEVELOPER_MESSAGE, self::HUMAN_MESSAGE);
    }
}
