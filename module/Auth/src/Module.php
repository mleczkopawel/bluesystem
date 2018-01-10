<?php
/**
 * @link      http://github.com/zendframework/Auth for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Auth;

use Auth\Service\AuthManager;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\MvcEvent;
use Zend\Session\SessionManager;

/**
 * Class Module
 * @package Auth
 */
class Module implements ConfigProviderInterface {

    /**
     * @return mixed
     */
    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    /**
     * @param MvcEvent $mvcEvent
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function onBootstrap(MvcEvent $mvcEvent) {
        $application = $mvcEvent->getApplication();
        $serviceManager = $application->getServiceManager();
        $sessionManager = $serviceManager->get(SessionManager::class);
        $eventManager = $mvcEvent->getApplication()->getEventManager();
        $sharedEventManager = $eventManager->getSharedManager();

        $sharedEventManager->attach(
            AbstractActionController::class, MvcEvent::EVENT_DISPATCH, [
            $this,
            'onDispatch'
        ], 100
        );
    }

    /**
     * @param MvcEvent $mvcEvent
     * @throws \Exception
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function onDispatch(MvcEvent $mvcEvent) {
        $controller = $mvcEvent->getTarget();
        $controllerName = $mvcEvent->getRouteMatch()->getParam('controller', null);
        $actionName = $mvcEvent->getRouteMatch()->getParam('action', null);
        $actionName = str_replace('-', '', lcfirst(ucwords($actionName, '-')));

        $authManager = $mvcEvent->getApplication()->getServiceManager()->get(AuthManager::class);

        if (!$authManager->filterAccess($controllerName, $actionName)) {
            if (!$authManager->hasIdentity() && 'Auth\Controller\AuthController' != $controllerName || 'Auth\Controller\OAuthController' != $controllerName) {
                $controller->plugin('redirect')->toUrl('/auth/login');
            } else {
                throw new \Exception('Not allowed', 5001);
            }
        }
    }
}
