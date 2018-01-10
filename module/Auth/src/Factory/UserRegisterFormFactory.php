<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 18.11.17
 * Time: 15:24
 */

namespace Auth\Factory;


use Auth\Entity\User;
use Auth\Filter\UserRegisterFilter;
use Auth\Form\UserRegisterForm;
use Interop\Container\ContainerInterface;
use Zend\Hydrator\ClassMethods;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class UserRegisterFormFactory
 * @package Auth\Factory
 */
class UserRegisterFormFactory implements FactoryInterface {

    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return UserRegisterForm
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        $form = new UserRegisterForm('register');

        $form->setHydrator($container->get('HydratorManager')->get(ClassMethods::class));
        $form->setInputFilter($container->get('InputFilterManager')->get(UserRegisterFilter::class));
        $form->setObject(new User());

        return $form;
    }
}