<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 08.01.18
 * Time: 18:45
 */

namespace Admin\Factory;


use Admin\Form\AddClientGroupForm;
use Interop\Container\ContainerInterface;
use Zend\Hydrator\ClassMethods;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class AddClientGroupFormFactory
 * @package Admin\Factory
 */
class AddClientGroupFormFactory implements FactoryInterface {

    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return AddClientGroupForm|object
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        $form = new AddClientGroupForm('add_client_group');

        $form->setHydrator($container->get('HydratorManager')->get(ClassMethods::class));

        return $form;
    }
}