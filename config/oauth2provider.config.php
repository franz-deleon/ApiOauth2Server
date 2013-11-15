<?php
return array(
    'servers' => array(
        'mediaapi' => array(
            'storages' => array(
                'user_credentials' => new \OAuth2ProviderTests\Assets\Storage\UserCredentialsStorage(),
                'access_token'  => new \OAuth2ProviderTests\Assets\Storage\AccessTokenStorage(),
                'refresh_token' => new \OAuth2ProviderTests\Assets\Storage\RefreshTokenStorage(),
            ),
            'grant_types' => array(
                'user_credentials',
            ),
            'response_types' => array(
                array(
                    'name' => 'OAuth2\ResponseType\AccessToken',
                    'params' => array(
                    ),
                ),
            ),
            'token_type' => 'bearer',
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