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
        parent::__construct(self::STATUS_CODE, self::ERROR_CODE, self::DEVELOPER_MESSAGE, self::HUMAN_MESSAGE);
    }
}
