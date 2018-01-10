<?php

namespace Mail\Service\Factory;

use Mail\Service\Mailer;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class MailerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $templateMap = $container->get('Config')['view_manager']['template_map'];
        $smtp = $container->get('Config')['smtp'];

        return new Mailer($templateMap, $smtp);
    }
}