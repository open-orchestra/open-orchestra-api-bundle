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
        parent::__construct(self::STATUS_CODE, self::ERROR_CODE, self::DEVELOPER_MESSAGE, self::HUMAN_MESSAGE);
    }
}
