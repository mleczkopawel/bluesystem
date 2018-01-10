<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 08.01.18
 * Time: 12:55
 */

namespace Admin\Factory;


use Admin\Form\ChangeClientUserGroupForm;
use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Zend\Hydrator\ClassMethods;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class ChangeClientUserGroupFormFactory
 * @package Admin\Factory
 */
class ChangeClientUserGroupFormFactory implements FactoryInterface {

    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return ChangeClientUserGroupForm|object
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        $form = new ChangeClientUserGroupForm('change-client-user-group', [], $container->get(EntityManager::class));

        $form->setHydrator($container->get('HydratorManager')->get(ClassMethods::class));

        return $form;
    }
}