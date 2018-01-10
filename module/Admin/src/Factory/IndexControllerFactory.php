<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 23.11.17
 * Time: 09:07
 */

namespace Admin\Factory;


use Admin\Controller\IndexController;
use Auth\Service\FlashMessengerService;
use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class IndexControllerFactory
 * @package Admin\Controller\Factory
 */
class IndexControllerFactory implements FactoryInterface {

    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return IndexController|object
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        try {
            return new IndexController(
                $container->get(EntityManager::class),
                $container->get(FlashMessengerService::class)
            );
        } catch (NotFoundExceptionInterface $e) {
            echo $e->getMessage();
        } catch (ContainerExceptionInterface $e) {
            echo $e->getMessage();
        }
    }
}