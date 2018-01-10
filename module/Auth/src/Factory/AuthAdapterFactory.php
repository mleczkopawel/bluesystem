<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 17.11.17
 * Time: 23:44
 */

namespace Auth\Factory;


use Auth\Service\AuthAdapter;
use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class AuthAdapterFactory
 * @package Auth\Factory
 */
class AuthAdapterFactory implements FactoryInterface {

    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return AuthAdapter
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        return new AuthAdapter(
            $container->get(EntityManager::class)
        );
    }
}