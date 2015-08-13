<?php

namespace OpenOrchestra\BaseApiBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use OpenOrchestra\BaseApiBundle\Controller\Annotation as Api;

/**
 * Class AuthorizationController
 *
 * @Api\Serialize()
 */
class AuthorizationController extends BaseController
{
    /**
     * @param Request $request
     *
     * @return Response
     */
    public function accessTokenAction(Request $request)
    {
        return $this->get('open_orchestra_api.oauth2.authorization_server')->requestToken($request);
    }
}
