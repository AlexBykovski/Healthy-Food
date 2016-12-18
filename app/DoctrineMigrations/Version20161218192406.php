<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20161218192406 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE diet_additional_information (id BIGINT AUTO_INCREMENT NOT NULL, count_eating INT DEFAULT 3 NOT NULL, count_training INT DEFAULT 0 NOT NULL, training_difficulty INT DEFAULT 1 NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id BIGINT AUTO_INCREMENT NOT NULL, diet_additional_information_id BIGINT DEFAULT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(128) NOT NULL, created_at DATETIME NOT NULL, forgot_password_key VARCHAR(128) DEFAULT NULL, photo VARCHAR(255) DEFAULT NULL, height VARCHAR(255) DEFAULT NULL, weight VARCHAR(255) DEFAULT NULL, gender VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D6497BE538B6 (diet_additional_information_id), UNIQUE INDEX user_email (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6497BE538B6 FOREIGN KEY (diet_additional_information_id) REFERENCES diet_additional_information (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6497BE538B6');
        $this->addSql('DROP TABLE diet_additional_information');
        $this->addSql('DROP TABLE user');
    }
}
