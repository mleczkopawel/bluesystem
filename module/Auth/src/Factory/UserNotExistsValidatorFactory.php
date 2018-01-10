<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 16.11.17
 * Time: 09:27
 */

namespace Auth\Factory;


use Auth\Validator\UserNotExistsValidator;
use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class UserNotExistsValidatorFactory
 * @package Auth\Factory
 */
class UserNotExistsValidatorFactory implements FactoryInterface {

    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return UserNotExistsValidator
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        try {
            return new UserNotExistsValidator(
                $container->get(EntityManager::class)
            );
        } catch (NotFoundExceptionInterface $e) {
        } catch (ContainerExceptionInterface $e) {
        }
    }
}