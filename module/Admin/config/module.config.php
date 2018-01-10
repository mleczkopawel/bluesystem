<?php
namespace Admin;

use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;
use Zend\ServiceManager\Factory\InvokableFactory;

return [
    'router' => [
        'routes' => [
            'admin' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/admin[/:action[/:id]]',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action' => 'index',
                    ],
                ],
            ],
            'apps' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/apps[/:action[/:id]]',
                    'defaults' => [
                        'controller' => Controller\ClientsController::class,
                        'action' => 'index',
                    ],
                ],
            ],
            'users' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/users[/:action[/:id]]',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action' => 'index',
                    ],
                ],
            ],
        ],
    ],
    'navigation' => [
        'default' => [
            [
                'label' => 'Dashboard',
                'route' => 'admin',
                'icon' => 'fa fa-dashboard',
            ],
            [
                'label' => 'Aplikacje',
                'route' => 'apps',
                'icon' => 'fa fa-microchip',
                'pages' => [
                    [
                        'label' => 'Dodaj nową',
                        'route' => 'apps',
                        'action' => 'add'
                    ]
                ]
            ],
            [
                'label' => 'Użytkownicy',
                'route' => 'users',
                'icon' => 'fa fa-user',
                'pages' => [
                    [
                        'label' => 'Dodaj nowego',
                        'route' => 'users',
                        'action' => 'add',
                    ],
                    [
                        'label' => 'Lista',
                        'route' => 'users',
                        'action' => 'list',
                    ]
                ]
            ]
        ]
    ],
    'controllers' => [
        'factories' => [
            Controller\IndexController::class => Factory\IndexControllerFactory::class,
            Controller\ClientsController::class => Factory\ClientsControllerFactory::class,
        ],
    ],
    'service_manager' => [
        'factories' => [
            'SystemNavigation' => Factory\SystemNavigationFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
            Form\AddClientAppForm::class => Factory\AddClientAppFormFactory::class,
            Form\ChangeClientUserGroupForm::class => Factory\ChangeClientUserGroupFormFactory::class,
            Form\AddClientGroupForm::class => Factory\AddClientGroupFormFactory::class,
        ],
    ],
    'access_filter' => [
        'options' => [
            'mode' => 'restrictive',
        ],
        'controllers' => [
            Controller\IndexController::class => [
                [
                    'allow' => '1',
                    'actions' => ['index'],
                ],
            ],
        ],
    ],
    'asset_manager' => [
        'resolver_configs' => [
            'paths' => [
                'Admin' => __DIR__ . '/../public'
            ],
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            'Admin' => __DIR__ . '/../view',
        ],
        'template_map' => [
            'admin/layout' => __DIR__ . '/../view/layout/layout.phtml',
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
