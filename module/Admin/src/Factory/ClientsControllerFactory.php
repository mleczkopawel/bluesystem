<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 06.01.18
 * Time: 22:47
 */

namespace Admin\Factory;


use Admin\Controller\ClientController;
use Admin\Form\AddClientAppForm;
use Admin\Form\AddClientGroupForm;
use Admin\Form\ChangeClientUserGroupForm;
use Auth\Service\FlashMessengerService;
use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class ClientsControllerFactory
 * @package Admin\Factory
 */
class ClientsControllerFactory implements FactoryInterface {

    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return ClientController|object
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        return new ClientController(
            $container->get(EntityManager::class),
            $container->get('FormElementManager')->get(AddClientAppForm::class),
            $container->get(FlashMessengerService::class),
            $container->get('FormElementManager')->get(ChangeClientUserGroupForm::class),
            $container->get('FormElementManager')->get(AddClientGroupForm::class),
            $container->get('Config')['superuser']
        );
    }
}