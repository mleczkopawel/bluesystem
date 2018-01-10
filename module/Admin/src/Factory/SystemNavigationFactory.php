<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 06.01.18
 * Time: 21:37
 */

namespace Admin\Factory;


use Admin\Navigation\SystemNavigation;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class SystemNavigationFactory
 * @package Auth\Factory
 */
class SystemNavigationFactory implements FactoryInterface {

    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return object|\Zend\Navigation\Navigation
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        $config = $container->get('Config');
        return (new SystemNavigation($config))->createService($container);
    }
}