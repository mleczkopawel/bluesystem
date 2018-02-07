<?php
namespace Log;

use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Zend\Router\Http\Segment;
use Zend\ServiceManager\Factory\InvokableFactory;

return [
    'router' => [
        'routes' => [
            'admin' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/admin',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action' => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'log' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/log[/:action[/:id]]',
                            'defaults' => [
                                'controller' => Controller\IndexController::class,
                                'action' => 'index',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\IndexController::class => Factory\IndexControllerFactory::class,
        ],
    ],
    'service_manager' => [
        'factories' => [
            Service\FileCreator::class => Factory\FileCreatorFactory::class,
            Service\EntityCreator::class => Factory\EntityCreatorFactory::class,
        ],
    ],
    'controller_plugins' => [
        'factories' => [
            Plugin\LoggerPlugin::class => Factory\LoggerPluginFactory::class,
        ],
        'aliases' => [
            'logger' => Plugin\LoggerPlugin::class,
        ],
    ],
    'navigation' => [
        'default' => [
            [
                'label' => 'Logi',
                'route' => 'admin/log',
                'icon' => 'fa fa-empire',
                'pages' => [
                    [
                        'label' => 'Dodaj logi',
                        'route' => 'admin/log',
                        'action' => 'add'
                    ],
                    [
                        'label' => 'Lista',
                        'route' => 'admin/log',
                        'action' => 'list'
                    ]
                ]
            ],
        ]
    ],
    'access_filter' => [
        'options' => [
            'mode' => 'restrictive',
        ],
        'controllers' => [
            Controller\IndexController::class => [
                [
                    'allow' => '@',
                    'actions' => ['index', 'add', 'list'],
                ],
            ],
        ],
    ],
    'asset_manager' => [
        'resolver_configs' => [
            'paths' => [
                'Log' => __DIR__ . '/../public'
            ],
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            'Log' => __DIR__ . '/../view',
        ],
        'template_map' => [
            'creator/main-entity' => __DIR__ . '/../view/creator/entity.phtml',
        ],
    ],
    'doctrine' => [
        'driver' => [
            __NAMESPACE__ . '_driver' => [
                'class' => AnnotationDriver::class,
                'cache' => 'array',
                'paths' => [__DIR__ . '/../src/Entity'],
            ],
            'orm_default' => [
                'drivers' => [
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver',
                ],
            ],
        ],
    ],
];
