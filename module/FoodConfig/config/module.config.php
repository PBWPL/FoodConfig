<?php
/**
 * Created by PhpStorm.
 * User: piotrbec
 * Date: 2019-04-03
 * Time: 14:28
 */

namespace FoodConfig;

use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Laminas\Session\SessionManager;
use Laminas\Authentication\Storage\Session as SessionStorage;

return [
    'controllers' => [
        'factories' => [
            // VerifyController
            Controller\VerifyController::class => function($container, $requestedName) {
                $authManager = $container->get('auth_manager');
                $entityManager = $container->get('doctrine.entitymanager.orm_default');
                $config = $container->get('config');
                $recapchaKey = $config['recapcha'];
                $mailService = $container->get('mail_service');
                return new $requestedName($authManager, $entityManager, $recapchaKey, $mailService);
            },
            // RoleController
            Controller\RoleController::class => function($container, $requestedName) {
                $entityManager = $container->get('doctrine.entitymanager.orm_default');
                $config = $container->get('config');
                $aclConfig = $config['acl_config'];
                return new $requestedName($aclConfig, $entityManager);
            },
            // UserController
            Controller\UserController::class => function($container, $requestedName) {
                $entityManager = $container->get('doctrine.entitymanager.orm_default');
                $authManager = $container->get('auth_manager');
                $config = $container->get('config');
                $mailService = $container->get('mail_service');
                $aclConfig = $config['acl_config'];
                return new $requestedName($aclConfig, $mailService, $entityManager, $authManager);
            },
            // IndexController
            Controller\DishController::class => function($container, $requestedName) {
                $entityManager = $container->get('doctrine.entitymanager.orm_default');
                $authManager = $container->get('auth_manager');
                $config = $container->get('config');
                $aclConfig = $config['acl_config'];
                return new $requestedName($aclConfig, $entityManager, $authManager);
            },
            // GuestController
            Controller\GuestController::class => function($container, $requestedName) {
                $entityManager = $container->get('doctrine.entitymanager.orm_default');
                $config = $container->get('config');
                $aclConfig = $config['acl_config'];
                $authManager = $container->get('auth_manager');
                $recapchaKey = $config['recapcha'];
                $mailService = $container->get('mail_service');
                return new $requestedName($aclConfig, $entityManager, $authManager, $recapchaKey, $mailService);
            }
        ],
    ],
    'service_manager' => [
        'factories' => [
            // AuthManager
            Service\AuthManager::class => function($container, $requestedName) {
                $entityManager = $container->get('doctrine.entitymanager.orm_default');
                $authService = $container->get('auth_service');
                $sessionManager = $container->get(SessionManager::class);
                return new $requestedName($authService, $sessionManager, $entityManager);
            },
            // AuthAdapter
            Service\AuthAdapter::class => function($container, $requestedName) {
                $entityManager = $container->get('doctrine.entitymanager.orm_default');
                return new $requestedName($entityManager);
            },
            // AuthenticationService
            \Laminas\Authentication\AuthenticationService::class => function($container, $requestedName) {
                $sessionManager = $container->get(SessionManager::class);
                $authStorage = new SessionStorage('Zend_Auth','session',$sessionManager);
                $authAdapter = $container->get('auth_adapter');
                return new $requestedName($authStorage, $authAdapter);
            },
            // AclManager
            Service\AclManager::class => function($container, $requestedName) {
                $authManager = $container->get('auth_manager');
                $config = $container->get('config');
                $aclConfig = $config['acl_config'];
                return new $requestedName($authManager, $aclConfig);
            },
            // MailService
            Service\MailService::class => function($container, $requestedName) {
                return new $requestedName();
            },
            'admin_nav' => Navigation\AdminNavigationFactory::class
        ],
        'aliases' => [
            'auth_manager' => Service\AuthManager::class,
            'auth_adapter' => Service\AuthAdapter::class,
            'auth_service' => \Laminas\Authentication\AuthenticationService::class,
            'acl_manager' => Service\AclManager::class,
            'mail_service'  => Service\MailService::class,
        ],
    ],

    'view_manager' => [
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => [
            'index/index' => __DIR__ . '/../view/food/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ],
        'strategies' => [
            'ViewJsonStrategy',
        ],
        'template_path_stack' => [
            'FoodConfig' => __DIR__ . '/../view',
        ],
    ],

    // AclConfig (Controllers and Actions)
    'acl_config' => [
        'Verify' => [
            'resource'      => 'foodconfig:verifycontroller',
            'privileges'    => ['index', 'login', 'register', 'logout', 'forgot', 'active', 'denied']
        ],
        'Role' => [
            'resource'      => 'foodconfig:rolecontroller',
            'privileges'    => ['index', 'create', 'store', 'edit', 'update', 'delete']
        ],
        'Dish' => [
            'resource'      => 'foodconfig:dishcontroller',
            'privileges'    => ['index', 'create', 'remove']
        ],
        'User' => [
            'resource'      => 'foodconfig:usercontroller',
            'privileges'    => ['index', 'like', 'unlike', 'shoppinglist', 'profile', 'create', 'store', 'edit', 'update', 'delete']
        ],
        'Guest' => [
            'resource' => 'foodconfig:guestcontroller',
            'privileges' => ['search', 'dish', 'policy', 'contact']
        ]
    ],

    'recapcha' => [
        'public_key' => '', // edit
        'private_key' => '' // edit
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

    'navigation' => [
        'admin_nav' => [
            [
                'label' => 'Role',
                'route' => 'foodconfig/role',
                'resource' => 'foodconfig:rolecontroller',
                'privilege' => 'index',
            ],
            [
                'label' => 'Użytkownicy',
                'route' => 'foodconfig/user',
                'resource' => 'foodconfig:usercontroller',
                'privilege' => 'index'
            ],
            [
                'label' => 'Dodaj danie',
                'route' => 'foodconfig/dish',
                'resource' => 'foodconfig:dishcontroller',
                'privilege' => 'index'
            ]
        ],
    ],
];
