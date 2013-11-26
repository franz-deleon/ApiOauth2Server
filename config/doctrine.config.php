<?php
return array(
    'driver' => array(
        'annotation_driver' => array(
            'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
            'cache' => 'apc',
            'paths' => array(
                realpath(__DIR__ . '/../src/ApiOauth2Server/Model/Entity'),
            ),
        ),
        'orm_default' => array(
            'drivers' => array(
                'ApiOauth2Server\Model\Entity' => 'annotation_driver',
            )
        )
    ),
    'configuration' => array(
        'orm_default' => array(
            'query_cache'    => 'apc',
            'result_cache'   => 'apc',
            'metadata_cache' => 'apc',
        ),
    ),
    'connection' => array(
        'orm_default' => array(
            'doctrine_type_mappings' => array(
                'ENUM' => 'string'
            ),
        ),
    ),
);