<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221006104136 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE cocktail (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ingredient (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cocktails_ingredients (ingredient_id INT NOT NULL, cocktail_id INT NOT NULL, INDEX IDX_A3A9C460933FE08C (ingredient_id), INDEX IDX_A3A9C460CD6F76C6 (cocktail_id), PRIMARY KEY(ingredient_id, cocktail_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE cocktails_ingredients ADD CONSTRAINT FK_A3A9C460933FE08C FOREIGN KEY (ingredient_id) REFERENCES ingredient (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE cocktails_ingredients ADD CONSTRAINT FK_A3A9C460CD6F76C6 FOREIGN KEY (cocktail_id) REFERENCES cocktail (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cocktails_ingredients DROP FOREIGN KEY FK_A3A9C460933FE08C');
        $this->addSql('ALTER TABLE cocktails_ingredients DROP FOREIGN KEY FK_A3A9C460CD6F76C6');
        $this->addSql('DROP TABLE cocktail');
        $this->addSql('DROP TABLE ingredient');
        $this->addSql('DROP TABLE cocktails_ingredients');
    }
}
