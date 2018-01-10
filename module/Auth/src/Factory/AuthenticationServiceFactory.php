<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 18.11.17
 * Time: 00:19
 */

namespace Auth\Factory;


use Auth\Service\AuthAdapter;
use Interop\Container\ContainerInterface;
use Zend\Authentication\AuthenticationService;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\Session\SessionManager;
use Zend\Authentication\Storage\Session as SessionStorage;

/**
 * Class AuthenticationServiceFactory
 * @package Auth\Factory
 */
class AuthenticationServiceFactory implements FactoryInterface {

    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return AuthenticationService
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null):AuthenticationService {
        $sessionManager = $container->get(SessionManager::class);
        $authStorage = new SessionStorage('Zend_Auth', 'session', $sessionManager);
        $authAdapter = $container->get(AuthAdapter::class);

        return new AuthenticationService($authStorage, $authAdapter);
    }
}