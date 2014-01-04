<?php
return array(
    'servers' => array(
        'mediaapi' => array(
            'storages' => array(
                'client_credentials' => 'ApiOauth2Server/Storage/ClientCredentials',
                'user_credentials'   => 'ApiOauth2Server/Storage/UserCredentials',
                'access_token'       => 'ApiOauth2Server/Storage/AccessToken',
                'refresh_token'      => 'ApiOauth2Server/Storage/RefreshToken',
            ),
            'response_types' => array(
                array(
                    'name' => 'ApiOauth2Server\ResponseType\AccessToken',
                )
            ),
            'grant_types' => array(
                'user_credentials',
                'refresh_token' => array(
                    'options' => array(
                        'configs' => array(
                            'always_issue_new_refresh_token' => false,
                        ),
                    ),
                )
            ),
            'scope_util' => array(
                'name' => 'scope',
                'options' => array(
                    'default_scope' => 'get',
                    'supported_scopes' => array('get', 'put', 'post', 'delete'),
                ),
            ),
            'version' => 'v1',
            'controller' => 'ApiOauth2Server\Controller\UserCredentialsController',
        ),
    ),
    'main_server'  => 'mediaapi',
    'main_version' => 'v1',
    'default_controller' => 'ApiOauth2Server\Controller\UserCredentialsController',
);
