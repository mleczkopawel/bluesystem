<?php
namespace Auth;

use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Log\Factory\LoggerPluginFactory;
use Log\Plugin\LoggerPlugin;
use Zend\Authentication\AuthenticationService;
use Zend\Router\Http\Segment;
use Zend\ServiceManager\Factory\InvokableFactory;
use Zend\Session\Storage\SessionArrayStorage;

return [
    'router' => [
        'routes' => [
            'oauth' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/oauth[/:action]',
                    'defaults' => [
                        'controller' => Controller\OAuthController::class,
                        'action' => 'index',
                    ],
                ],
            ],
            'auth' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/auth[/:action]',
                    'defaults' => [
                        'controller' => Controller\AuthController::class,
                    ]
                ]
            ]
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\OAuthController::class => Factory\OAuthControllerFactory::class,
            Controller\AuthController::class => Factory\AuthControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
            Form\UserLoginForm::class => Factory\UserLoginFormFactory::class,
            Form\UserRegisterForm::class => Factory\UserRegisterFormFactory::class,
        ]
    ],
    'input_filters' => [
        'factories' => [
            Filter\UserLoginFilter::class => InvokableFactory::class,
            Filter\UserRegisterFilter::class => InvokableFactory::class,
        ],
    ],
    'validators' => [
        'factories' => [
            Validator\PasswordStrengthValidator::class => InvokableFactory::class,
            Validator\UserNotExistsValidator::class => Factory\UserNotExistsValidatorFactory::class,
            Validator\UserEmailExistsValidator::class => Factory\UserEmailExistsValidatorFactory::class,
            Validator\UserLoginExistsValidator::class => Factory\UserLoginExistsValidatorFactory::class,
        ],
    ],
    'service_manager' => [
        'factories' => [
            Service\OAuthService::class => Factory\OAuthServiceFactory::class,
            Service\FlashMessengerService::class => InvokableFactory::class,
            Service\AuthAdapter::class => Factory\AuthAdapterFactory::class,
            Service\AuthManager::class => Factory\AuthManagerFactory::class,
            Service\UserManager::class => Factory\UserManagerFactory::class,
            AuthenticationService::class => Factory\AuthenticationServiceFactory::class,
        ],
    ],
    'controller_plugins' => [
        'factories' => [
            Plugin\OAuthPlugin::class => Factory\OAuthPluginFactory::class,
//            LoggerPlugin::class => LoggerPluginFactory::class,
        ],
        'aliases' => [
            'oauth' => Plugin\OAuthPlugin::class,
//            'logger' => LoggerPlugin::class
        ],
    ],
    'asset_manager' => [
        'resolver_configs' => [
            'paths' => [
                'Auth' => __DIR__ . '/../public'
            ],
        ],
    ],
    'view_manager' => [
        'template_map' => [
            'auth/layout' => __DIR__ . '/../view/layout/layout.phtml',
            'auth/elayout' => __DIR__ . '/../view/layout/email.phtml',
            'email/register' => __DIR__ . '/../view/email/register.phtml',
            'email/change' => __DIR__ . '/../view/email/change.phtml',
        ],
        'template_path_stack' => [
            'Auth' => __DIR__ . '/../view',
        ],
        'strategies' => [
            'ViewJsonStrategy',
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
    'session_config' => [
        'cookie_lifetime' => 60 * 60 * 24,
        'gc_maxlifetime' => 60 * 60 * 24,
    ],
    'session_storage' => [
        'type' => SessionArrayStorage::class,
    ],
    'access_filter' => [
        'options' => [
            'mode' => 'restrictive',
        ],
        'controllers' => [
            Controller\AuthController::class => [
                [
                    'allow' => '*',
                    'actions' => ['login', 'logout', 'register', 'makeNewPassword', 'createNewPassword'],
                ],
            ],
            Controller\OAuthController::class => [
                [
                    'allow' => '*',
                    'actions' => ['getToken', 'getUserId', 'index', 'register'],
                ],
            ],

        ],
    ],
];
