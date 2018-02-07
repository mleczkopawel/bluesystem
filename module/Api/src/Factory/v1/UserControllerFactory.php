<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 30.01.18
 * Time: 18:32
 */

namespace Api\Factory\v1;


use Api\Controller\v1\UserController;
use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class UserControllerFactory
 * @package Api\Factory\v1
 */
class UserControllerFactory implements FactoryInterface {

    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return UserController|object
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        return new UserController(
            $container->get(EntityManager::class)
        );
    }
}