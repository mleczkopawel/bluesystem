<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 27.01.18
 * Time: 19:41
 */

namespace Admin\Factory;


use Admin\Controller\UserController;
use Admin\Form\AddUserForm;
use Admin\Form\UserGroupForm;
use Auth\Service\FlashMessengerService;
use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class UserControllerFactory
 * @package Admin\Factory
 */
class UserControllerFactory implements FactoryInterface {

    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return UserController|object
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        return new UserController(
            $container->get(EntityManager::class),
            $container->get(FlashMessengerService::class),
            $container->get('FormElementManager')->get(AddUserForm::class),
            $container->get('FormElementManager')->get(UserGroupForm::class)
        );
    }
}