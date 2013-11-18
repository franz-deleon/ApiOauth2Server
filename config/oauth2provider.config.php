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
                'refresh_token'
            ),
            'scope_util' => array(
                'name' => 'scope',
                'options' => array(
                    'default_scope' => 'read',
                    'supported_scopes' => array('read', 'write', 'delete'),
                ),
            ),
        ),
    ),

    /**
     * Main Primary Server
     *
     * Define by picking the "main server" to use from the server configurations list/keys above.
     * You can access the main server using the through the main service manager by:
     *
     * <code>
     * $sm->get('oauth2provider.server.main');
     * </code>
     *
     * Defaults to: default
     */
    'main_server' => 'mediaapi',

    /**
     * Controller
     *
     * Define which controller to use:
     */
    'controller' => 'OAuth2Provider\Controller\UserCredentialsController',
);