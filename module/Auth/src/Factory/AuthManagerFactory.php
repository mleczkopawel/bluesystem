<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 18.11.17
 * Time: 00:13
 */

namespace Auth\Factory;


use Auth\Service\AuthManager;
use Interop\Container\ContainerInterface;
use Zend\Authentication\AuthenticationService;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\Session\SessionManager;

/**
 * Class AuthManagerFactory
 * @package Auth\Factory
 */
class AuthManagerFactory implements FactoryInterface {

    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return AuthManager
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null):AuthManager {
        $config = $container->get('Config');
        if (isset($config['access_filter'])) {
            $config = $config['access_filter'];
        } else {
            $config = [];
        }

        return new AuthManager(
            $container->get(AuthenticationService::class),
            $container->get(SessionManager::class),
            $config
        );
    }
}