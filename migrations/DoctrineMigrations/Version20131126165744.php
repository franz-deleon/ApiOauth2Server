<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20131126165744 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");

        // populate users
        $this->addSql(
            "INSERT INTO `mediaapi_oauth2`.`oauth_users` (`user_id`, `user_name`, `password`, `first_name`, `last_name`, `created`) VALUES (1, :username, :password, 'admin', 'administrator', CURRENT_TIMESTAMP);",
            array('username' => 'admin', 'password' => sha1('p@ssw0rd'))
        );

        // populate clients
        $this->addSql(
            "INSERT INTO `mediaapi_oauth2`.`oauth_clients` (`client_id`, `client_secret`, `redirect_uri`, `grant_types`, `created`) VALUES ('1', :client_secret, '/uri-not-needed', 'user_credentials', CURRENT_TIMESTAMP);",
            array('client_secret' => 'loc')
        );

        // populate users_clients
        $this->addSql(
            "INSERT INTO `mediaapi_oauth2`.`users_clients` (`user_id`, `client_id`) VALUES ('1', '1');"
        );

        // populate scope
        $this->addSql(
            "INSERT INTO `mediaapi_oauth2`.`oauth_scopes` (`scope_id`, `client_id`, `user_id`, `type`, `scope`) VALUES ('1', '1', '1', 'admin', 'get post put delete');"
        );
    }

    public function down(Schema $schema)
    {
        // refresh tokens
        $this->addSql("DELETE FROM `mediaapi_oauth2`.`oauth_refresh_tokens`");

        // access tokens
        $this->addSql("DELETE FROM `mediaapi_oauth2`.`oauth_access_tokens`");

        // scope
        $this->addSql("DELETE FROM `mediaapi_oauth2`.`oauth_scopes` WHERE `scope_id`='1'");

        // users_clients
        $this->addSql("DELETE FROM `mediaapi_oauth2`.`users_clients` WHERE `user_id`='1' AND `client_id`='1'");

        // clients
        $this->addSql("DELETE FROM `mediaapi_oauth2`.`oauth_clients` WHERE `client_id`='1'");

        // users
        $this->addSql("DELETE FROM `mediaapi_oauth2`.`oauth_users` WHERE `user_id`= 1");
    }
}
