<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20131126165731 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("CREATE TABLE oauth_access_tokens (access_token VARCHAR(255) NOT NULL, client_id INT DEFAULT NULL, user_id INT DEFAULT NULL, expires DATETIME NOT NULL, scope VARCHAR(50) DEFAULT NULL, created TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL, INDEX IDX_CA42527C19EB6921 (client_id), INDEX IDX_CA42527CA76ED395 (user_id), PRIMARY KEY(access_token)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE oauth_clients (client_id INT NOT NULL, client_secret VARCHAR(100) NOT NULL, redirect_uri VARCHAR(255) DEFAULT NULL, grant_types VARCHAR(50) DEFAULT NULL, created TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(client_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE oauth_refresh_tokens (refresh_token VARCHAR(100) NOT NULL, client_id INT DEFAULT NULL, user_id INT DEFAULT NULL, expires DATETIME NOT NULL, used ENUM('yes', 'no') COMMENT '(DC2Type:enumyesno)' NOT NULL, scope VARCHAR(50) DEFAULT NULL, created TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL, INDEX IDX_5AB68719EB6921 (client_id), INDEX IDX_5AB687A76ED395 (user_id), PRIMARY KEY(refresh_token)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE oauth_scopes (scope_id INT AUTO_INCREMENT NOT NULL, client_id INT DEFAULT NULL, user_id INT DEFAULT NULL, type VARCHAR(100) NOT NULL, scope VARCHAR(50) NOT NULL, INDEX IDX_6EE3C02819EB6921 (client_id), INDEX IDX_6EE3C028A76ED395 (user_id), PRIMARY KEY(scope_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE oauth_users (user_id INT AUTO_INCREMENT NOT NULL, user_name VARCHAR(50) NOT NULL, password VARCHAR(100) NOT NULL, first_name VARCHAR(100) DEFAULT NULL, last_name VARCHAR(100) DEFAULT NULL, created TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL, UNIQUE INDEX UNIQ_93804FF824A232CF (user_name), PRIMARY KEY(user_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE users_clients (user_id INT NOT NULL, client_id INT NOT NULL, INDEX IDX_F0C85ABEA76ED395 (user_id), INDEX IDX_F0C85ABE19EB6921 (client_id), PRIMARY KEY(user_id, client_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("ALTER TABLE oauth_access_tokens ADD CONSTRAINT FK_CA42527C19EB6921 FOREIGN KEY (client_id) REFERENCES oauth_clients (client_id)");
        $this->addSql("ALTER TABLE oauth_access_tokens ADD CONSTRAINT FK_CA42527CA76ED395 FOREIGN KEY (user_id) REFERENCES oauth_users (user_id)");
        $this->addSql("ALTER TABLE oauth_refresh_tokens ADD CONSTRAINT FK_5AB68719EB6921 FOREIGN KEY (client_id) REFERENCES oauth_clients (client_id)");
        $this->addSql("ALTER TABLE oauth_refresh_tokens ADD CONSTRAINT FK_5AB687A76ED395 FOREIGN KEY (user_id) REFERENCES oauth_users (user_id)");
        $this->addSql("ALTER TABLE oauth_scopes ADD CONSTRAINT FK_6EE3C02819EB6921 FOREIGN KEY (client_id) REFERENCES oauth_clients (client_id)");
        $this->addSql("ALTER TABLE oauth_scopes ADD CONSTRAINT FK_6EE3C028A76ED395 FOREIGN KEY (user_id) REFERENCES oauth_users (user_id)");
        $this->addSql("ALTER TABLE users_clients ADD CONSTRAINT FK_F0C85ABEA76ED395 FOREIGN KEY (user_id) REFERENCES oauth_users (user_id)");
        $this->addSql("ALTER TABLE users_clients ADD CONSTRAINT FK_F0C85ABE19EB6921 FOREIGN KEY (client_id) REFERENCES oauth_clients (client_id)");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("ALTER TABLE oauth_access_tokens DROP FOREIGN KEY FK_CA42527C19EB6921");
        $this->addSql("ALTER TABLE oauth_refresh_tokens DROP FOREIGN KEY FK_5AB68719EB6921");
        $this->addSql("ALTER TABLE oauth_scopes DROP FOREIGN KEY FK_6EE3C02819EB6921");
        $this->addSql("ALTER TABLE users_clients DROP FOREIGN KEY FK_F0C85ABE19EB6921");
        $this->addSql("ALTER TABLE oauth_access_tokens DROP FOREIGN KEY FK_CA42527CA76ED395");
        $this->addSql("ALTER TABLE oauth_refresh_tokens DROP FOREIGN KEY FK_5AB687A76ED395");
        $this->addSql("ALTER TABLE oauth_scopes DROP FOREIGN KEY FK_6EE3C028A76ED395");
        $this->addSql("ALTER TABLE users_clients DROP FOREIGN KEY FK_F0C85ABEA76ED395");
        $this->addSql("DROP TABLE oauth_access_tokens");
        $this->addSql("DROP TABLE oauth_clients");
        $this->addSql("DROP TABLE oauth_refresh_tokens");
        $this->addSql("DROP TABLE oauth_scopes");
        $this->addSql("DROP TABLE oauth_users");
        $this->addSql("DROP TABLE users_clients");
    }
}
