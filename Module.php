<?php
namespace ApiOauth2Server;

class Module
{
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getModuleDependencies()
    {
        return array(
            'DoctrineORMModule',
            'OAuth2Provider',
        );
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'doctrine.cache.oauth2filesystem' => function ($sm) {
                    return new \Doctrine\Common\Cache\FilesystemCache(
                        __DIR__ . '/../../data/cache/doctrine'
                    );
                },
                'doctrine.cache.oauth2apc' => function ($sm) {
                    return new \Doctrine\Common\Cache\ApcCache();
                },
                'cacheFactory' => 'Zend\Cache\Service\StorageCacheFactory',
            ),
        );
    }
}
