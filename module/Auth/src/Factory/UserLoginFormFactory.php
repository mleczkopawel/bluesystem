<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 17.11.17
 * Time: 23:04
 */

namespace Auth\Factory;


use Auth\Entity\User;
use Auth\Filter\UserLoginFilter;
use Auth\Form\UserLoginForm;
use Interop\Container\ContainerInterface;
use Zend\Hydrator\ClassMethods;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class UserLoginFormFactory
 * @package Auth\Factory
 */
class UserLoginFormFactory implements FactoryInterface {

    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return UserLoginForm
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        $form = new UserLoginForm('login');

        $form->setHydrator($container->get('HydratorManager')->get(ClassMethods::class));
        $form->setInputFilter($container->get('InputFilterManager')->get(UserLoginFilter::class));

        return $form;
    }
}