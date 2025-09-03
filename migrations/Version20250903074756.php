<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250903074756 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE utilisateur_outing (utilisateur_id INT NOT NULL, outing_id INT NOT NULL, INDEX IDX_930C3CAEFB88E14F (utilisateur_id), INDEX IDX_930C3CAEAF4C7531 (outing_id), PRIMARY KEY(utilisateur_id, outing_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE utilisateur_outing ADD CONSTRAINT FK_930C3CAEFB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE utilisateur_outing ADD CONSTRAINT FK_930C3CAEAF4C7531 FOREIGN KEY (outing_id) REFERENCES outing (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE outing ADD utilisateur_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE outing ADD CONSTRAINT FK_F2A10625FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id)');
        $this->addSql('CREATE INDEX IDX_F2A10625FB88E14F ON outing (utilisateur_id)');
        $this->addSql('ALTER TABLE utilisateur ADD campus_id INT DEFAULT NULL, ADD last_name VARCHAR(100) NOT NULL, ADD first_name VARCHAR(100) NOT NULL, ADD phone_number VARCHAR(50) NOT NULL, ADD actif TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE utilisateur ADD CONSTRAINT FK_1D1C63B3AF5D55E1 FOREIGN KEY (campus_id) REFERENCES utilisateur (id)');
        $this->addSql('CREATE INDEX IDX_1D1C63B3AF5D55E1 ON utilisateur (campus_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE utilisateur_outing DROP FOREIGN KEY FK_930C3CAEFB88E14F');
        $this->addSql('ALTER TABLE utilisateur_outing DROP FOREIGN KEY FK_930C3CAEAF4C7531');
        $this->addSql('DROP TABLE utilisateur_outing');
        $this->addSql('ALTER TABLE outing DROP FOREIGN KEY FK_F2A10625FB88E14F');
        $this->addSql('DROP INDEX IDX_F2A10625FB88E14F ON outing');
        $this->addSql('ALTER TABLE outing DROP utilisateur_id');
        $this->addSql('ALTER TABLE utilisateur DROP FOREIGN KEY FK_1D1C63B3AF5D55E1');
        $this->addSql('DROP INDEX IDX_1D1C63B3AF5D55E1 ON utilisateur');
        $this->addSql('ALTER TABLE utilisateur DROP campus_id, DROP last_name, DROP first_name, DROP phone_number, DROP actif');
    }
}
