<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170408120210 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE recipe_nutrient');
        $this->addSql('ALTER TABLE recipe ADD fats DOUBLE PRECISION NOT NULL, ADD proteins DOUBLE PRECISION NOT NULL, ADD carbohydrates DOUBLE PRECISION NOT NULL, ADD calories DOUBLE PRECISION NOT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE recipe_nutrient (id BIGINT AUTO_INCREMENT NOT NULL, recipe_id BIGINT DEFAULT NULL, name VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, count DOUBLE PRECISION NOT NULL, INDEX IDX_DF0689C459D8A214 (recipe_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE recipe_nutrient ADD CONSTRAINT FK_DF0689C459D8A214 FOREIGN KEY (recipe_id) REFERENCES recipe (id)');
        $this->addSql('ALTER TABLE recipe DROP fats, DROP proteins, DROP carbohydrates, DROP calories');
    }
}
