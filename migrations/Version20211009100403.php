<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211009100403 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE domicile ADD rencontre_id INT NOT NULL');
        $this->addSql('ALTER TABLE domicile ADD CONSTRAINT FK_F6305DA26CFC0818 FOREIGN KEY (rencontre_id) REFERENCES rencontre (id)');
        $this->addSql('CREATE INDEX IDX_F6305DA26CFC0818 ON domicile (rencontre_id)');
        $this->addSql('ALTER TABLE visiteur ADD rencontre_id INT NOT NULL');
        $this->addSql('ALTER TABLE visiteur ADD CONSTRAINT FK_4EA587B86CFC0818 FOREIGN KEY (rencontre_id) REFERENCES rencontre (id)');
        $this->addSql('CREATE INDEX IDX_4EA587B86CFC0818 ON visiteur (rencontre_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE domicile DROP FOREIGN KEY FK_F6305DA26CFC0818');
        $this->addSql('DROP INDEX IDX_F6305DA26CFC0818 ON domicile');
        $this->addSql('ALTER TABLE domicile DROP rencontre_id');
        $this->addSql('ALTER TABLE visiteur DROP FOREIGN KEY FK_4EA587B86CFC0818');
        $this->addSql('DROP INDEX IDX_4EA587B86CFC0818 ON visiteur');
        $this->addSql('ALTER TABLE visiteur DROP rencontre_id');
    }
}
