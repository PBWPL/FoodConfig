<?php
/**
 * Created by PhpStorm.
 * User: piotrbec
 * Date: 2019-04-03
 * Time: 14:29
 */

namespace FoodConfig;

use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;

return [
    'router' => [
        'routes' => [
            'home' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/',
                    'defaults' => [
                        'controller' => Controller\GuestController::class,
                        'action'     => 'search',
                    ],
                ],
            ],
            'search' => [
                'type' => Segment::class,
                'options' => [
                    'route'    => '/page[/:page]',
                    'constraints' => [
                        'page'        => '[0-9]+'
                    ],
                    'defaults' => [
                        'page' => 1,
                        'controller' => Controller\GuestController::class,
                        'action'     => 'search',
                    ],
                ],
            ],
            'dish' => [
                'type' => Segment::class,
                'options' => [
                    'route'    => '/dish[/:id]',
                    'constraints' => [
                        'id'        => '[0-9]+'
                    ],
                    'defaults' => [
                        'id' => 1,
                        'controller' => Controller\GuestController::class,
                        'action'     => 'dish',
                    ],
                ],
            ],
            'profile' => [
                'type' => Segment::class,
                'options' => [
                    'route'    => '/profile[/:id]',
                    'constraints' => [
                        'id'        => '[0-9]+'
                    ],
                    'defaults' => [
                        'controller' => Controller\UserController::class,
                        'action'     => 'profile',
                    ],
                ],
            ],
            'contact' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/contact',
                    'defaults' => [
                        'controller'    => Controller\GuestController::class,
                        'action'        => 'contact',
                    ],
                ],
            ],
            'policy' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/policy',
                    'defaults' => [
                        'controller'    => Controller\GuestController::class,
                        'action'        => 'policy',
                    ],
                ],
            ],
            'foodconfig' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/foodconfig',
                    'defaults' => [
                        'controller'    => Controller\GuestController::class,
                        'action'        => 'search',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'verify' => [
                        'type'    => Segment::class,
                        'options' => [
                            'route'    => '/verify[/:action[/email[/:email[/token[/:token]]]]]',
                            'constraints' => [
                                'action'    => '[a-zA-Z][a-zA-Z0-9_-]+',
                                'token'    => '[a-zA-Z0-9_-]+',
                            ],
                            'defaults' => [
                                'controller' => Controller\VerifyController::class,
                                'action'     => 'index',
                            ],
                        ],
                    ],
                    'role' => [
                        'type'    => Segment::class,
                        'options' => [
                            'route'    => '/role[/:action[/:id]]',
                            'constraints' => [
                                'action'    => '[a-zA-Z][a-zA-Z0-9_-]+',
                                'id'        => '[0-9]+'
                            ],
                            'defaults' => [
                                'controller' => Controller\RoleController::class,
                                'action'     => 'index',
                            ],
                        ],
                    ],
                    'user' => [
                        'type'    => Segment::class,
                        'options' => [
                            'route'    => '/user[/:action[/:id]]',
                            'constraints' => [
                                'action'    => '[a-zA-Z][a-zA-Z0-9_-]+',
                                'id'        => '[0-9]+'
                            ],
                            'defaults' => [
                                'controller' => Controller\UserController::class,
                                'action'     => 'index',
                            ],
                        ],
                    ],
                    'dish' => [
                        'type'    => Segment::class,
                        'options' => [
                            'route'    => '/dish[/:action[/:id]]',
                            'constraints' => [
                                'action'    => '[a-zA-Z][a-zA-Z0-9_-]+',
                                'id'        => '[0-9]+'
                            ],
                            'defaults' => [
                                'controller' => Controller\DishController::class,
                                'action'     => 'create',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
];