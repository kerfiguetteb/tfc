<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211104094008 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE entraineur_section (entraineur_id INT NOT NULL, section_id INT NOT NULL, INDEX IDX_791E8730F8478A1 (entraineur_id), INDEX IDX_791E8730D823E37A (section_id), PRIMARY KEY(entraineur_id, section_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE entraineur_section ADD CONSTRAINT FK_791E8730F8478A1 FOREIGN KEY (entraineur_id) REFERENCES entraineur (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE entraineur_section ADD CONSTRAINT FK_791E8730D823E37A FOREIGN KEY (section_id) REFERENCES section (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE entraineur ADD groupe_id INT NOT NULL, ADD categories_id INT NOT NULL');
        $this->addSql('ALTER TABLE entraineur ADD CONSTRAINT FK_3D247E877A45358C FOREIGN KEY (groupe_id) REFERENCES groupe (id)');
        $this->addSql('ALTER TABLE entraineur ADD CONSTRAINT FK_3D247E87A21214B7 FOREIGN KEY (categories_id) REFERENCES categorie (id)');
        $this->addSql('CREATE INDEX IDX_3D247E877A45358C ON entraineur (groupe_id)');
        $this->addSql('CREATE INDEX IDX_3D247E87A21214B7 ON entraineur (categories_id)');
        $this->addSql('ALTER TABLE joueur ADD entraineur_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE joueur ADD CONSTRAINT FK_FD71A9C5F8478A1 FOREIGN KEY (entraineur_id) REFERENCES entraineur (id)');
        $this->addSql('CREATE INDEX IDX_FD71A9C5F8478A1 ON joueur (entraineur_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE entraineur_section');
        $this->addSql('ALTER TABLE entraineur DROP FOREIGN KEY FK_3D247E877A45358C');
        $this->addSql('ALTER TABLE entraineur DROP FOREIGN KEY FK_3D247E87A21214B7');
        $this->addSql('DROP INDEX IDX_3D247E877A45358C ON entraineur');
        $this->addSql('DROP INDEX IDX_3D247E87A21214B7 ON entraineur');
        $this->addSql('ALTER TABLE entraineur DROP groupe_id, DROP categories_id');
        $this->addSql('ALTER TABLE joueur DROP FOREIGN KEY FK_FD71A9C5F8478A1');
        $this->addSql('DROP INDEX IDX_FD71A9C5F8478A1 ON joueur');
        $this->addSql('ALTER TABLE joueur DROP entraineur_id');
    }
}
