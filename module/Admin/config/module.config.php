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
                    'route' => '/admin',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action' => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'dashboard' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/dashboard[/:action[/:id]]',
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
                                'controller' => Controller\ClientController::class,
                                'action' => 'index',
                            ],
                        ],
                    ],
                    'user' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/user[/:action[/:id]]',
                            'defaults' => [
                                'controller' => Controller\UserController::class,
                                'action' => 'index',
                            ],
                        ],
                    ],
                ]
            ],
        ],
    ],
    'console' => [
        'router' => [
            'routes' => [
                'create-super-user' => [
                    'options' => [
                        'route' => 'create-super-user',
                        'defaults' => [
                            'controller' => Controller\IndexController::class,
                            'action' => 'createSuperUser',
                        ],
                    ],
                ],
                'create-user' => [
                    'options' => [
                        'route' => 'create-user',
                        'defaults' => [
                            'controller' => Controller\IndexController::class,
                            'action' => 'createUser',
                        ],
                    ],
                ],
            ],
        ],
    ],
    'navigation' => [
        'default' => [
            [
                'label' => 'Dashboard',
                'route' => 'admin/dashboard',
                'icon' => 'fa fa-dashboard',
            ],
            [
                'label' => 'Aplikacje',
                'route' => 'admin/apps',
                'icon' => 'fa fa-microchip',
                'pages' => [
                    [
                        'label' => 'Dodaj aplikację',
                        'route' => 'admin/apps',
                        'action' => 'add'
                    ],
                    [
                        'label' => 'Lista',
                        'route' => 'admin/apps',
                        'action' => 'list'
                    ]
                ]
            ],
            [
                'label' => 'Użytkownicy',
                'route' => 'admin/user',
                'icon' => 'fa fa-user',
                'pages' => [
                    [
                        'label' => 'Dodaj użytkownika',
                        'route' => 'admin/user',
                        'action' => 'add',
                    ],
                    [
                        'label' => 'Lista',
                        'route' => 'admin/user',
                        'action' => 'list',
                    ]
                ]
            ]
        ]
    ],
    'controllers' => [
        'factories' => [
            Controller\IndexController::class => Factory\IndexControllerFactory::class,
            Controller\ClientController::class => Factory\ClientsControllerFactory::class,
            Controller\UserController::class => Factory\UserControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
            Form\AddClientAppForm::class => Factory\AddClientAppFormFactory::class,
            Form\ChangeClientUserGroupForm::class => Factory\ChangeClientUserGroupFormFactory::class,
            Form\AddClientGroupForm::class => Factory\AddClientGroupFormFactory::class,
            Form\AddUserForm::class => Factory\AddUserFormFactory::class,
            Form\UserGroupForm::class => Factory\UserGroupFormFactory::class,
        ],
    ],
    'access_filter' => [
        'options' => [
            'mode' => 'restrictive',
        ],
        'controllers' => [
            Controller\IndexController::class => [
                [
                    'allow' => '@',
                    'actions' => ['index'],
                ],
            ],
            Controller\ClientController::class => [
                [
                    'allow' => '1',
                    'actions' => ['add', 'show', 'edit', 'changeUserGroup', 'addClientGroup'],
                ],
            ],
            Controller\UserController::class => [
                [
                    'allow' => '1',
                    'actions' => ['add', 'list', 'userGroup', 'remove'],
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
