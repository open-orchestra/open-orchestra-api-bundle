<?php

namespace OpenOrchestra\BaseApi\Exceptions\HttpException;

/**
 * Class ClientNonTrustedHttpException
 */
class ClientNonTrustedHttpException extends ApiException
{
    const DEVELOPER_MESSAGE  = 'client.non_trusted';
    const HUMAN_MESSAGE      = 'open_orchestra_api.client.client_non_trusted';
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
