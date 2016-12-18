<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20161218193141 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE diet_additional_information ADD user_id BIGINT DEFAULT NULL');
        $this->addSql('ALTER TABLE diet_additional_information ADD CONSTRAINT FK_C450D5D4A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C450D5D4A76ED395 ON diet_additional_information (user_id)');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6497BE538B6');
        $this->addSql('DROP INDEX UNIQ_8D93D6497BE538B6 ON user');
        $this->addSql('ALTER TABLE user DROP diet_additional_information_id');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE diet_additional_information DROP FOREIGN KEY FK_C450D5D4A76ED395');
        $this->addSql('DROP INDEX UNIQ_C450D5D4A76ED395 ON diet_additional_information');
        $this->addSql('ALTER TABLE diet_additional_information DROP user_id');
        $this->addSql('ALTER TABLE user ADD diet_additional_information_id BIGINT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6497BE538B6 FOREIGN KEY (diet_additional_information_id) REFERENCES diet_additional_information (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D6497BE538B6 ON user (diet_additional_information_id)');
    }
}
