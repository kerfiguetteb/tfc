<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211107173144 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE entraineur ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE entraineur ADD CONSTRAINT FK_3D247E87A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_3D247E87A76ED395 ON entraineur (user_id)');
        $this->addSql('ALTER TABLE joueur ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE joueur ADD CONSTRAINT FK_FD71A9C5A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_FD71A9C5A76ED395 ON joueur (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE entraineur DROP FOREIGN KEY FK_3D247E87A76ED395');
        $this->addSql('DROP INDEX UNIQ_3D247E87A76ED395 ON entraineur');
        $this->addSql('ALTER TABLE entraineur DROP user_id');
        $this->addSql('ALTER TABLE joueur DROP FOREIGN KEY FK_FD71A9C5A76ED395');
        $this->addSql('DROP INDEX UNIQ_FD71A9C5A76ED395 ON joueur');
        $this->addSql('ALTER TABLE joueur DROP user_id');
    }
}
