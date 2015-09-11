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
        parent::__construct(self::STATUS_CODE, self::ERROR_CODE, self::DEVELOPER_MESSAGE, self::HUMAN_MESSAGE);
    }
}
