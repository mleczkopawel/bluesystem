<?php
/**
 * @link      http://github.com/zendframework/Auth for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Auth\Controller;

use Auth\Service\OAuthService;
use OAuth2\Request;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;

/**
 * Class OAuthController
 * @package Auth\Controller
 */
class OAuthController extends AbstractActionController {

    /**
     * @var OAuthService
     */
    private $oAuthService;

    /**
     * OAuthController constructor.
     * @param OAuthService $OAuthService
     */
    public function __construct(OAuthService $OAuthService) {
        $this->oAuthService = $OAuthService;
        $this->oAuthService->initServer();
    }


    /**
     * @return JsonModel
     */
    public function indexAction() {
        $response = null;
        if ($this->oauth()->auth()) {
            $response = [
                'success' => 'YouGotIt',
            ];
        }

        return new JsonModel($response);
    }


    /**
     * @return void
     */
    public function getTokenAction() {
        $this->oAuthService->getServer()->handleTokenRequest(Request::createFromGlobals())->send();die;
    }

    /**
     * @return JsonModel
     */
    public function getUserIdAction() {
        $response = null;
        if ($this->oauth()->auth()) {
            $token = $this->oAuthService->getServer()->getAccessTokenData(Request::createFromGlobals());
            $response = [
                'user_id' => $token['user_id'],
            ];
        }

        return new JsonModel($response);
    }
}
