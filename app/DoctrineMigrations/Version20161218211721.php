<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20161218211721 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE diet_additional_information ADD purpose VARCHAR(255) DEFAULT \'Сбросить вес\' NOT NULL');
        $this->addSql('ALTER TABLE user CHANGE height height DOUBLE PRECISION NOT NULL, CHANGE weight weight DOUBLE PRECISION NOT NULL, CHANGE gender gender TINYINT(1) NOT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE diet_additional_information DROP purpose');
        $this->addSql('ALTER TABLE user CHANGE height height VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, CHANGE weight weight VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, CHANGE gender gender VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci');
    }
}
