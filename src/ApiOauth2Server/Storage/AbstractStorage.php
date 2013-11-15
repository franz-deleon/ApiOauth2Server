<?php
namespace ApiOauth2Server\Storage;

use Zend\ServiceManager;

abstract class AbstractStorage implements ServiceManager\ServiceLocatorAwareInterface
{
    protected $serviceLocator;

    /**
     * Convert array keys from camelcased to underscore
     *
     * @param array $array
     * @return string|multitype:
     */
    protected function convertCamelKeysToUnderscore(array $array)
    {
        $camelcaseFilter = $this->getServiceLocator()
            ->get('FilterManager')
            ->get('wordcamelcasetounderscore');

        $camelcasedKeys = array_map(function ($val) use ($camelcaseFilter) {
            return strtolower($camelcaseFilter->filter($val));
        }, array_keys($array));

        return array_combine($camelcasedKeys, $array);
    }

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