<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 02.02.18
 * Time: 21:32
 */

namespace Admin\Factory;


use Admin\Form\UserGroupForm;
use Interop\Container\ContainerInterface;
use Zend\Hydrator\ClassMethods;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class UserGroupFormFactory
 * @package Admin\Factory
 */
class UserGroupFormFactory implements FactoryInterface {

    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return UserGroupForm|object
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        $form = new UserGroupForm('user-group');

        $form->setHydrator($container->get('HydratorManager')->get(ClassMethods::class));

        return $form;
    }
}