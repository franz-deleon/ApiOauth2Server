<?php
return array(
    'db' => array(
        'connection' => 'rsora11gt.loctest.gov:1521/owsd.loctest.gov',
        'username'   => 'mediaown',
        'password'   => 'd7ApHeVeY8tr',
    ),
    'phpSettings' => array(
        'display_startup_errors'        => true,
        'display_errors'                => true,
        'error_reporting'               => true
    ),
    'doctrine' => array(
        'connection' => array(
            // default connection name
            'orm_default' => array(
                'driverClass' => 'Doctrine\DBAL\Driver\OCI8\Driver',
                'params' => array(
                    'host'     => '192.168.2.2',
                    'port'     => '1521',
                    'dbname'   => 'XE',
                    'user'     => 'MEDIAOAUTHOWN',
                    'password' => 'password',
                    'platform' => new \ApiOauth2Server\Platform\LocalOci8Platform(), // quick hack for our local sql format ;(
                ),
            )
        )
    ),
);