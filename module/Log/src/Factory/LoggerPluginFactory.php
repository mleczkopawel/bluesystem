<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 03.02.18
 * Time: 23:50
 */

namespace Log\Factory;


use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Log\Plugin\LoggerPlugin;
use Zend\Log\Logger;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class LoggerPluginFactory
 * @package Log\Factory
 */
class LoggerPluginFactory implements FactoryInterface {

    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return LoggerPlugin|object
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        $logger = new Logger();
        $config = $container->get('Config');

        return new LoggerPlugin(
            $logger,
            $container->get(EntityManager::class),
            $config['logger']
        );
    }
}