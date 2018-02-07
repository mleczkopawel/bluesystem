<?php
namespace Api;

use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;
use Zend\ServiceManager\Factory\InvokableFactory;

return [
    'router' => [
        'routes' => [
            'api' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/api',
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'v1' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/v1',
                        ],
                        'may_terminate' => true,
                        'child_routes' => [
                            'user' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/user[/:id]',
                                    'defaults' => [
                                        'controller' => Controller\v1\UserController::class
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'v2' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/v2',
                        ],
                        'may_terminate' => true,
                        'child_routes' => [
                            'user' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/user[/:id]',
                                    'defaults' => [
                                        'controller' => Controller\v2\UserController::class
                                    ]
                                ]
                            ]
                        ],
                    ],
                ],
            ],
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\v1\UserController::class => Factory\v1\UserControllerFactory::class,
            Controller\v2\UserController::class => InvokableFactory::class
        ],
    ],
    'view_manager' => [
        'strategies' => [
            'ViewJsonStrategy',
        ],
    ],
];
