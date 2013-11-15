<?php
return array(
    'service_manager' => array(
        'factories' => array(

        ),
        'invokables' => array(
            'ApiOauth2Server/Storage/UserCredentials' => 'ApiOauth2Server\Storage\UserCredentials',
        ),
    ),
    'doctrine' => include_once __DIR__ . '/doctrine.config.php',
    'oauth2provider' => include_once __DIR__ . '/oauth2provider.config.php',
);