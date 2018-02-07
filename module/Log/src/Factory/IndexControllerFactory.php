<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 03.02.18
 * Time: 21:44
 */

namespace Log\Factory;


use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Log\Controller\IndexController;
use Log\Service\EntityCreator;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class IndexControllerFactory
 * @package Log\Factory
 */
class IndexControllerFactory implements FactoryInterface {

    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return IndexController|object
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        return new IndexController(
            $container->get(EntityManager::class),
            $container->get(EntityCreator::class)
        );
    }
}