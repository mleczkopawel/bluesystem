<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 06.01.18
 * Time: 14:50
 */

namespace Auth\Factory;


use Auth\Plugin\OAuthPlugin;
use Auth\Service\OAuthService;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class OAuthPluginFactory
 * @package Auth\Factory
 */
class OAuthPluginFactory implements FactoryInterface {

    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return OAuthPlugin|object
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        return new OAuthPlugin(
            $container->get(OAuthService::class)
        );
    }
}