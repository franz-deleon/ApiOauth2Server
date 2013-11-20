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
            'grant_types' => array(
                'user_credentials',
                'refresh_token' => array(
                    'options' => array(
                        'configs' => array(
                            'always_issue_new_refresh_token' => true,
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
        ),
    ),
    'main_server' => 'mediaapi',
    'controller' => 'OAuth2Provider\Controller\UserCredentialsController',
);
