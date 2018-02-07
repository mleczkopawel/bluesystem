<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 06.02.18
 * Time: 23:23
 */

namespace Log\Factory;


use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Log\Service\EntityCreator;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class EntityCreatorFactory
 * @package Log\Factory
 */
class EntityCreatorFactory implements FactoryInterface {

    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return EntityCreator|object
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        return new EntityCreator(
            $container->get(EntityManager::class)
        );
    }
}