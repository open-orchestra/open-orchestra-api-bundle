<?php

namespace OpenOrchestra\BaseApi\Exceptions\HttpException;

/**
 * Class TokenNotFoundHttpException
 */
class TokenNotFoundHttpException extends ApiException
{
    const DEVELOPER_MESSAGE  = 'token.not_found';
    const HUMAN_MESSAGE      = 'api.exception.token_not_found';
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
