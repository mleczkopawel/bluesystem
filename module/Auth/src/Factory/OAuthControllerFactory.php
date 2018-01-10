<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 05.01.18
 * Time: 23:50
 */

namespace Auth\Factory;


use Auth\Controller\OAuthController;
use Auth\Service\OAuthService;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class OAuthControllerFactory
 * @package Auth\Factory
 */
class OAuthControllerFactory implements FactoryInterface {

    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return OAuthController|object
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        try {
            return new OAuthController(
                $container->get(OAuthService::class)
            );
        } catch (NotFoundExceptionInterface $e) {
        } catch (ContainerExceptionInterface $e) {
        }
    }
}