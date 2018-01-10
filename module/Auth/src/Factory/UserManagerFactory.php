<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 19.11.17
 * Time: 08:14
 */

namespace Auth\Factory;


use Auth\Service\UserManager;
use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class UserManagerFactory
 * @package Auth\Factory
 */
class UserManagerFactory implements FactoryInterface {

    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return UserManager
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        return new UserManager(
            $container->get(EntityManager::class)
        );
    }
}