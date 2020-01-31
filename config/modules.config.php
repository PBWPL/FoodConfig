<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

/**
 * List of enabled modules for this food-config.
 *
 * This should be an array of module namespaces used in the food-config.
 */
return [
    'Zend\Mvc\Plugin\FlashMessenger',
    'Zend\Mail',
    'Zend\Paginator',
    'Zend\Navigation',
    'Zend\Session',
    'Zend\Log',
    'Zend\Form',
    'Zend\Db',
    'Zend\Cache',
    'Zend\Router',
    'Zend\View\HelperPluginManager',
    'Zend\Validator',
    'DoctrineModule',
    'DoctrineORMModule',
    'FoodConfig'
];
