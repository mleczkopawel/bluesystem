<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

/**
 * Class Module
 * @package Application
 */
class Module
{
    /**
     *
     */
    const VERSION = '3.0.3-dev';

    /**
     *
     */
    const APP_NAME = 'BlueSystem';

    /**
     * @return mixed
     */
    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }
}
