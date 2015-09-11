<?php

namespace OpenOrchestra\BaseApi\Exceptions\HttpException;

/**
 * Class BadClientCredentialsHttpException
 */
class BadClientCredentialsHttpException extends ApiException
{
    const DEVELOPER_MESSAGE  = 'client.bad_credentials';
    const HUMAN_MESSAGE      = 'open_orchestra_api.client.bad_credentials';
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
