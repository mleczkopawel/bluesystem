<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 06.01.18
 * Time: 19:32
 */

namespace Auth\Factory;


use Auth\Controller\AuthController;
use Auth\Form\UserLoginForm;
use Auth\Form\UserRegisterForm;
use Auth\Service\AuthManager;
use Auth\Service\FlashMessengerService;
use Auth\Service\UserManager;
use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Mail\Service\MailManager;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class AuthControllerFactory
 * @package Auth\Factory
 */
class AuthControllerFactory implements FactoryInterface {

    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return AuthController|object
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        return new AuthController(
            $container->get(EntityManager::class),
            $container->get('FormElementManager')->get(UserLoginForm::class),
            $container->get(FlashMessengerService::class),
            $container->get(AuthManager::class),
            $container->get(UserManager::class),
            $container->get(MailManager::class),
            $container->get('FormElementManager')->get(UserRegisterForm::class)
        );
    }
}