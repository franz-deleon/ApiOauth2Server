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
            'types' => array(
                'enumyesno' => 'ApiOauth2Server\Model\Type\EnumYesNoType',
            )
        ),
    ),
    'connection' => array(
        'orm_default' => array(
            'doctrine_type_mappings' => array(
                'enumyesno' => 'enumyesno',
                'enum' => 'string',
            ),
        ),
    ),
    'migrations_configuration' => array(
        'orm_default' => array(
            'directory' => realpath(__DIR__ . '/../migrations/DoctrineMigrations'),
            'name' => 'ApiOauth2Server Database Migrations',
            'namespace' => 'DoctrineMigrations',
            'table' => 'migrations',
        ),
    ),
);