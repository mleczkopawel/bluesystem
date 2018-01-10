<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 05.01.18
 * Time: 23:07
 */

namespace Auth\Factory;


use Auth\Service\OAuthService;
use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class OAuthServiceFactory
 * @package Auth\Factory
 */
class OAuthServiceFactory implements FactoryInterface {

    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return OAuthService|object
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws \Exception
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        $config = $container->get('Config')['OAuth'];

        try {
            return new OAuthService(
                $container->get(EntityManager::class),
                $config
            );
        } catch (NotFoundExceptionInterface $e) {
            throw new \Exception($e->getMessage());
        } catch (ContainerExceptionInterface $e) {
            throw new \Exception($e->getMessage());
        }
    }
}