<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 17.11.17
 * Time: 23:49
 */

namespace Auth\Service;


use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Result;
use Zend\Session\Config\StandardConfig;
use Zend\Session\SessionManager;

/**
 * Class AuthManager
 * @package Auth\Service
 */
class AuthManager {

    /**
     * @var AuthenticationService
     */
    private $authenticationService;

    /**
     * @var SessionManager
     */
    private $sessionManager;

    /**
     * @var array
     */
    private $config;

    /**
     * @var array
     */
    const MESSAGE_TEMPLATES = [
        'Invalid credential' => 'Złe hasło'
    ];

    /**
     * AuthManager constructor.
     * @param AuthenticationService $authenticationService
     * @param SessionManager $sessionManager
     * @param array $config
     */
    public function __construct(AuthenticationService $authenticationService, SessionManager $sessionManager, array $config) {
        $this->authenticationService = $authenticationService;
        $this->sessionManager = $sessionManager;
        $this->config = $config;
    }

    /**
     * @param string $email
     * @param string $password
     * @param bool $rememberMe
     * @return Result
     */
    public function login(string $email, string $password, bool $rememberMe):Result {
        $this->authenticationService->getAdapter()->setEmail($email);
        $this->authenticationService->getAdapter()->setPassword($password);

        $result = $this->authenticationService->authenticate();

        if ($result->getCode() == Result::SUCCESS && $rememberMe) {
            $time = 60 * 60 * 60;
            $this->sessionManager->rememberMe($time);
            $config = new StandardConfig();
            $config->setOptions([
                'gc_maxlifetime' => $time,
                'cookie_lifetime' => $time,
                'remember_me_seconds' => $time,
            ]);

            $this->sessionManager->setConfig($config);
        }

        return $result;
    }

    /**
     *
     */
    public function logout() {
        $this->authenticationService->clearIdentity();
        $this->sessionManager->destroy();
    }

    /**
     * @return bool
     */
    public function hasIdentity() {
        return $this->authenticationService->hasIdentity();
    }

    /**
     * @return mixed|null
     */
    public function getIdentity() {
        return $this->authenticationService->getIdentity();
    }

    /**
     * @param $controllerName
     * @param $actionName
     * @return bool
     * @throws \Exception
     */
    public function filterAccess($controllerName, $actionName) {
        $mode = isset($this->config['options']['mode'])?$this->config['options']['mode']:'restrictive';
        if ($mode!='restrictive' && $mode!='permissive')
            throw new \Exception('Invalid access filter mode (expected either restrictive or permissive mode');

        if (isset($this->config['controllers'][$controllerName])) {
            $items = $this->config['controllers'][$controllerName];
            foreach ($items as $item) {
                $actionList = $item['actions'];
                $allow = $item['allow'];
                if (is_array($actionList) && in_array($actionName, $actionList) ||
                    $actionList=='*') {
                    if ($allow=='*' || ($this->authenticationService->hasIdentity() && $allow == $this->authenticationService->getIdentity()['group']))
                        return true; // Anyone is allowed to see the page.
                    else if ($allow=='@' && $this->authenticationService->hasIdentity()) {
                        return true; // Only authenticated user is allowed to see the page.
                    } else {
                        return false; // Access denied.
                    }
                }
            }
        }

        // In restrictive mode, we forbid access for unauthorized users to any
        // action not listed under 'access_filter' key (for security reasons).
        if ($mode=='restrictive' && !$this->authenticationService->hasIdentity())
            return false;

        // Permit access to this page.
        return true;
    }


}