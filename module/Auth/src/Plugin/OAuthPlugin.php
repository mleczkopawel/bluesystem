<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 06.01.18
 * Time: 14:47
 */

namespace Auth\Plugin;


use Auth\Service\OAuthService;
use OAuth2\Request;
use Zend\Mvc\Controller\Plugin\AbstractPlugin;

/**
 * Class OAuthPlugin
 * @package Auth\Plugin
 */
class OAuthPlugin extends AbstractPlugin {

    /**
     * @var OAuthService
     */
    private $oAuthService;

    /**
     * OAuthPlugin constructor.
     * @param OAuthService $OAuthService
     */
    public function __construct(OAuthService $OAuthService) {
        $this->oAuthService = $OAuthService;
    }

    /**
     * @return bool
     */
    public function auth() {
        if (!$this->oAuthService->getServer()->verifyResourceRequest(Request::createFromGlobals())) {
            $this->oAuthService->getServer()->getResponse()->send();
            die;
        } else {
            return true;
        }
    }

}