<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 18.11.17
 * Time: 15:29
 */

namespace Auth\Factory;


use Auth\Validator\UserLoginExistsValidator;
use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class UserEmailExistsValidatorFactory
 * @package Auth\Factory
 */
class UserLoginExistsValidatorFactory implements FactoryInterface {

    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return UserEmailExistsValidator|UserLoginExistsValidator
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        return new UserLoginExistsValidator(
            $container->get(EntityManager::class)
        );
    }
}