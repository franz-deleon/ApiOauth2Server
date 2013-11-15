<?php
namespace ApiOauth2Server\Storage;

use Zend\ServiceManager;

abstract class AbstractStorage implements ServiceManager\ServiceLocatorAwareInterface
{
    protected $serviceLocator;

    public function setServiceLocator(ServiceManager\ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
        return $this;
    }

    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }
}