<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 18.11.17
 * Time: 15:29
 */

namespace Auth\Factory;


use Auth\Validator\UserEmailExistsValidator;
use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class UserEmailExistsValidatorFactory
 * @package Auth\Factory
 */
class UserEmailExistsValidatorFactory implements FactoryInterface {

    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return UserEmailExistsValidator
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        return new UserEmailExistsValidator(
            $container->get(EntityManager::class)
        );
    }
}