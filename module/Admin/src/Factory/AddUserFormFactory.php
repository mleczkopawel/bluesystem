<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 28.01.18
 * Time: 12:06
 */

namespace Admin\Factory;


use Admin\Form\AddUserForm;
use Interop\Container\ContainerInterface;
use Zend\Hydrator\ClassMethods;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class AddUserFormFactory
 * @package Admin\Factory
 */
class AddUserFormFactory implements FactoryInterface {

    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return AddUserForm|object
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        $form = new AddUserForm('add-user');

        $form->setHydrator($container->get('HydratorManager')->get(ClassMethods::class));

        return $form;
    }
}