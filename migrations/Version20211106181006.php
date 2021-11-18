<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211106181006 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE entraineur DROP FOREIGN KEY FK_3D247E877A45358C');
        $this->addSql('ALTER TABLE groupe_categorie DROP FOREIGN KEY FK_19E905037A45358C');
        $this->addSql('ALTER TABLE joueur DROP FOREIGN KEY FK_FD71A9C57A45358C');
        $this->addSql('ALTER TABLE entraineur_section DROP FOREIGN KEY FK_791E8730D823E37A');
        $this->addSql('ALTER TABLE joueur DROP FOREIGN KEY FK_FD71A9C5D823E37A');
        $this->addSql('ALTER TABLE section_categorie DROP FOREIGN KEY FK_2B30A6B1D823E37A');
        $this->addSql('DROP TABLE entraineur_section');
        $this->addSql('DROP TABLE groupe');
        $this->addSql('DROP TABLE groupe_categorie');
        $this->addSql('DROP TABLE section');
        $this->addSql('DROP TABLE section_categorie');
        $this->addSql('DROP INDEX IDX_3D247E877A45358C ON entraineur');
        $this->addSql('ALTER TABLE entraineur DROP groupe_id');
        $this->addSql('DROP INDEX IDX_FD71A9C57A45358C ON joueur');
        $this->addSql('DROP INDEX IDX_FD71A9C5D823E37A ON joueur');
        $this->addSql('ALTER TABLE joueur DROP section_id, DROP groupe_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE entraineur_section (entraineur_id INT NOT NULL, section_id INT NOT NULL, INDEX IDX_791E8730F8478A1 (entraineur_id), INDEX IDX_791E8730D823E37A (section_id), PRIMARY KEY(entraineur_id, section_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE groupe (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE groupe_categorie (groupe_id INT NOT NULL, categorie_id INT NOT NULL, INDEX IDX_19E905037A45358C (groupe_id), INDEX IDX_19E90503BCF5E72D (categorie_id), PRIMARY KEY(groupe_id, categorie_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE section (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE section_categorie (section_id INT NOT NULL, categorie_id INT NOT NULL, INDEX IDX_2B30A6B1D823E37A (section_id), INDEX IDX_2B30A6B1BCF5E72D (categorie_id), PRIMARY KEY(section_id, categorie_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE entraineur_section ADD CONSTRAINT FK_791E8730D823E37A FOREIGN KEY (section_id) REFERENCES section (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE entraineur_section ADD CONSTRAINT FK_791E8730F8478A1 FOREIGN KEY (entraineur_id) REFERENCES entraineur (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE groupe_categorie ADD CONSTRAINT FK_19E905037A45358C FOREIGN KEY (groupe_id) REFERENCES groupe (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE groupe_categorie ADD CONSTRAINT FK_19E90503BCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE section_categorie ADD CONSTRAINT FK_2B30A6B1BCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE section_categorie ADD CONSTRAINT FK_2B30A6B1D823E37A FOREIGN KEY (section_id) REFERENCES section (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE entraineur ADD groupe_id INT NOT NULL');
        $this->addSql('ALTER TABLE entraineur ADD CONSTRAINT FK_3D247E877A45358C FOREIGN KEY (groupe_id) REFERENCES groupe (id)');
        $this->addSql('CREATE INDEX IDX_3D247E877A45358C ON entraineur (groupe_id)');
        $this->addSql('ALTER TABLE joueur ADD section_id INT NOT NULL, ADD groupe_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE joueur ADD CONSTRAINT FK_FD71A9C57A45358C FOREIGN KEY (groupe_id) REFERENCES groupe (id)');
        $this->addSql('ALTER TABLE joueur ADD CONSTRAINT FK_FD71A9C5D823E37A FOREIGN KEY (section_id) REFERENCES section (id)');
        $this->addSql('CREATE INDEX IDX_FD71A9C57A45358C ON joueur (groupe_id)');
        $this->addSql('CREATE INDEX IDX_FD71A9C5D823E37A ON joueur (section_id)');
    }
}
