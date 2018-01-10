<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 06.01.18
 * Time: 22:41
 */

namespace Admin\Factory;


use Admin\Form\AddClientAppForm;
use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Zend\Hydrator\ClassMethods;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class AddClientAppFormFactory
 * @package Admin\Factory
 */
class AddClientAppFormFactory implements FactoryInterface {

    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return AddClientAppForm|object
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        $form = new AddClientAppForm('add-client-app', [], $container->get(EntityManager::class));

        $form->setHydrator($container->get('HydratorManager')->get(ClassMethods::class));

        return $form;
    }
}