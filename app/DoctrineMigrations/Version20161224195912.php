<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20161224195912 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE recipe (id BIGINT AUTO_INCREMENT NOT NULL, photo VARCHAR(255) DEFAULT NULL, name VARCHAR(255) NOT NULL, time VARCHAR(255) DEFAULT NULL, portions INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE recipe_nutrient (id BIGINT AUTO_INCREMENT NOT NULL, recipe_id BIGINT DEFAULT NULL, name VARCHAR(255) NOT NULL, count DOUBLE PRECISION NOT NULL, INDEX IDX_DF0689C459D8A214 (recipe_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE recipe_product (id BIGINT AUTO_INCREMENT NOT NULL, recipe_id BIGINT DEFAULT NULL, name VARCHAR(255) NOT NULL, count DOUBLE PRECISION DEFAULT NULL, measure VARCHAR(255) DEFAULT NULL, INDEX IDX_9FAE0AED59D8A214 (recipe_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE recipe_step (id BIGINT AUTO_INCREMENT NOT NULL, recipe_id BIGINT DEFAULT NULL, photo VARCHAR(255) DEFAULT NULL, description VARCHAR(255) NOT NULL, INDEX IDX_3CA2A4E359D8A214 (recipe_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE recipe_nutrient ADD CONSTRAINT FK_DF0689C459D8A214 FOREIGN KEY (recipe_id) REFERENCES recipe (id)');
        $this->addSql('ALTER TABLE recipe_product ADD CONSTRAINT FK_9FAE0AED59D8A214 FOREIGN KEY (recipe_id) REFERENCES recipe (id)');
        $this->addSql('ALTER TABLE recipe_step ADD CONSTRAINT FK_3CA2A4E359D8A214 FOREIGN KEY (recipe_id) REFERENCES recipe (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE recipe_nutrient DROP FOREIGN KEY FK_DF0689C459D8A214');
        $this->addSql('ALTER TABLE recipe_product DROP FOREIGN KEY FK_9FAE0AED59D8A214');
        $this->addSql('ALTER TABLE recipe_step DROP FOREIGN KEY FK_3CA2A4E359D8A214');
        $this->addSql('DROP TABLE recipe');
        $this->addSql('DROP TABLE recipe_nutrient');
        $this->addSql('DROP TABLE recipe_product');
        $this->addSql('DROP TABLE recipe_step');
    }
}
