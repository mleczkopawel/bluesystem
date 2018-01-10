<?php

namespace Mail;

/**
 * @author jakub
 */
class Module
{
    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    public function getModuleDependencies()
    {
        return [
            'DoctrineModule',
            'DoctrineORMModule'
        ];
    }
}
