<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211223141538 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE joueur DROP FOREIGN KEY FK_FD71A9C5DD842E46');
        $this->addSql('DROP TABLE position');
        $this->addSql('DROP INDEX IDX_FD71A9C5DD842E46 ON joueur');
        $this->addSql('ALTER TABLE joueur ADD position VARCHAR(255) DEFAULT NULL, CHANGE position_id numero INT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE position (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE joueur DROP position, CHANGE numero position_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE joueur ADD CONSTRAINT FK_FD71A9C5DD842E46 FOREIGN KEY (position_id) REFERENCES position (id)');
        $this->addSql('CREATE INDEX IDX_FD71A9C5DD842E46 ON joueur (position_id)');
    }
}
