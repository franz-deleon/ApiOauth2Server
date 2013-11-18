<?php
return array(
    'service_manager' => array(
        'invokables' => array(
            /** Storages **/
            'ApiOauth2Server/Storage/ClientCredentials' => 'ApiOauth2Server\Storage\ClientCredentials',
            'ApiOauth2Server/Storage/UserCredentials'   => 'ApiOauth2Server\Storage\UserCredentials',
            'ApiOauth2Server/Storage/AccessToken'       => 'ApiOauth2Server\Storage\AccessToken',
            'ApiOauth2Server/Storage/RefreshToken'      => 'ApiOauth2Server\Storage\RefreshToken',

            /** Entities **/
            'ApiOauth2Server/Model/Entity/AccessToken'  => 'ApiOauth2Server\Model\Entity\OAuthAccessToken',
            'ApiOauth2Server/Model/Entity/Client'       => 'ApiOauth2Server\Model\Entity\OAuthClient',
            'ApiOauth2Server/Model/Entity/RefreshToken' => 'ApiOauth2Server\Model\Entity\OAuthRefreshToken',
            'ApiOauth2Server/Model/Entity/Scope'        => 'ApiOauth2Server\Model\Entity\OAuthScope',
            'ApiOauth2Server/Model/Entity/User'         => 'ApiOauth2Server\Model\Entity\OAuthUser',
        ),
        'shared' => array(
            'ApiOauth2Server/Model/Entity/AccessToken'  => false,
            'ApiOauth2Server/Model/Entity/Client'       => false,
            'ApiOauth2Server/Model/Entity/RefreshToken' => false,
            'ApiOauth2Server/Model/Entity/Scope'        => false,
            'ApiOauth2Server/Model/Entity/User'         => false,
        ),
    ),
    'doctrine' => include_once __DIR__ . '/doctrine.config.php',
    'oauth2provider' => include_once __DIR__ . '/oauth2provider.config.php',
);