<?php

namespace Mail\Controller\Factory;

use Mail\Controller\CronController;
use Interop\Container\ContainerInterface;
use Mail\Service\Mailer;
use Mail\Service\MailManager;
use Zend\ServiceManager\Factory\FactoryInterface;

class CronControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $mailer = $container->get(Mailer::class);
        $mailManager = $container->get(MailManager::class);

        return new CronController($entityManager, $mailer, $mailManager);
    }
}