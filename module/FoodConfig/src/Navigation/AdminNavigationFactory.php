<?php

/**
 * Created by PhpStorm.
 * User: piotrbec
 * Date: 2019-04-03
 * Time: 14:46
 */

namespace FoodConfig\Navigation;

use Laminas\Navigation\Service\AbstractNavigationFactory;
use Interop\Container\ContainerInterface;

/**
 * Admin navigation factory.
 */
class AdminNavigationFactory extends AbstractNavigationFactory
{
    /**
     * @return string
     */
    protected function getName()
    {
        return 'admin_nav';
    }
    /**
     * @param ContainerInterface $container
     * @return array
     * @throws \Laminas\Navigation\Exception\InvalidArgumentException
     */
    protected function getPages(ContainerInterface $container)
    {
        if (null === $this->pages) {
            $configuration = $container->get('config');
            if (!isset($configuration['navigation'])) {
                throw new \Laminas\Navigation\Exception\InvalidArgumentException('Could not find navigation configuration key');
            }
            if (!isset($configuration['navigation'][$this->getName()])) {
                throw new \Laminas\Navigation\Exception\InvalidArgumentException(sprintf(
                    'Failed to find a navigation container by the name "%s"',
                    $this->getName()
                ));
            }
            $pages       = $this->getPagesFromConfig($configuration['navigation'][$this->getName()]);
            $this->pages = $this->preparePages($container, $pages);
        }
        return $this->pages;
    }
}
