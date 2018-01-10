<?php

namespace Mail\Service\Factory;

use Mail\Service\MailManager;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class MailManagerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $entityManaget = $container->get('doctrine.entitymanager.orm_default');

        return new MailManager($entityManaget);
    }
}