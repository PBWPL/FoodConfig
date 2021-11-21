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
    'Laminas\ZendFrameworkBridge',
    'Laminas\Mvc\Plugin\FlashMessenger',
    'Laminas\Mail',
    'Laminas\Paginator',
    'Laminas\Navigation',
    'Laminas\Session',
    'Laminas\Log',
    'Laminas\Form',
    'Laminas\Db',
    'Laminas\Cache',
    'Laminas\Router',
    'Laminas\View\HelperPluginManager',
    'Laminas\Validator',
    'DoctrineModule',
    'DoctrineORMModule',
    'FoodConfig'
];
