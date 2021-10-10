<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211009082058 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE domicile (id INT AUTO_INCREMENT NOT NULL, equipe_id INT NOT NULL, visiteur_id INT NOT NULL, score INT NOT NULL, INDEX IDX_F6305DA26D861B89 (equipe_id), UNIQUE INDEX UNIQ_F6305DA27F72333D (visiteur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE equipe (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, pts INT NOT NULL, jo INT NOT NULL, ga INT NOT NULL, nu INT NOT NULL, pe INT NOT NULL, bp INT NOT NULL, bc INT NOT NULL, diff INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE journer (id INT AUTO_INCREMENT NOT NULL, date DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rencontre (id INT AUTO_INCREMENT NOT NULL, journer_id INT NOT NULL, INDEX IDX_460C35ED2C80E6E (journer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE visiteur (id INT AUTO_INCREMENT NOT NULL, equipe_id INT NOT NULL, score INT NOT NULL, INDEX IDX_4EA587B86D861B89 (equipe_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE domicile ADD CONSTRAINT FK_F6305DA26D861B89 FOREIGN KEY (equipe_id) REFERENCES equipe (id)');
        $this->addSql('ALTER TABLE domicile ADD CONSTRAINT FK_F6305DA27F72333D FOREIGN KEY (visiteur_id) REFERENCES visiteur (id)');
        $this->addSql('ALTER TABLE rencontre ADD CONSTRAINT FK_460C35ED2C80E6E FOREIGN KEY (journer_id) REFERENCES journer (id)');
        $this->addSql('ALTER TABLE visiteur ADD CONSTRAINT FK_4EA587B86D861B89 FOREIGN KEY (equipe_id) REFERENCES equipe (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE domicile DROP FOREIGN KEY FK_F6305DA26D861B89');
        $this->addSql('ALTER TABLE visiteur DROP FOREIGN KEY FK_4EA587B86D861B89');
        $this->addSql('ALTER TABLE rencontre DROP FOREIGN KEY FK_460C35ED2C80E6E');
        $this->addSql('ALTER TABLE domicile DROP FOREIGN KEY FK_F6305DA27F72333D');
        $this->addSql('DROP TABLE domicile');
        $this->addSql('DROP TABLE equipe');
        $this->addSql('DROP TABLE journer');
        $this->addSql('DROP TABLE rencontre');
        $this->addSql('DROP TABLE visiteur');
    }
}
