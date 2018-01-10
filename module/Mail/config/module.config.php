<?php
namespace Mail;

use Application\Controller\Factory\CronControllerFactory;
use Zend\Router\Http\Literal;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;

return [
    'router' => [
        'routes' => [
            'mailing' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/mailing',
                    'defaults' => [
                        'controller' => Controller\CronController::class,
                        'action'     => 'index'
                    ],
                ],
            ],
        ]
    ],
    'controllers' => [
        'factories' => [
            Controller\CronController::class => Controller\Factory\CronControllerFactory::class,
        ],
    ],
    'service_manager' => [
        'factories' => [
            Service\MailManager::class => Service\Factory\MailManagerFactory::class,
            Service\Mailer::class => Service\Factory\MailerFactory::class
        ],
    ],
    'doctrine' => [
        'driver' => [
            __NAMESPACE__ . '_driver' => [
                'class' => AnnotationDriver::class,
                'cache' => 'array',
                'paths' => [__DIR__ . '/../src/Entity']
            ],
            'orm_default' => [
                'drivers' => [
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
                ]
            ]
        ]
    ],
    'view_manager' => [
        'template_map' => [
            'layout/email'            => __DIR__ . '/../view/layout/email.phtml',
            'email/default'           => __DIR__ . '/../view/email/default.phtml'
        ]
    ]
];
