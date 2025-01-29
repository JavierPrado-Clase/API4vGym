<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250129183745 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE activities_monitors (activity_id INT NOT NULL, monitor_id INT NOT NULL, INDEX IDX_233F6A7281C06096 (activity_id), INDEX IDX_233F6A724CE1C902 (monitor_id), PRIMARY KEY(activity_id, monitor_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE activities_monitors ADD CONSTRAINT FK_233F6A7281C06096 FOREIGN KEY (activity_id) REFERENCES activity (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE activities_monitors ADD CONSTRAINT FK_233F6A724CE1C902 FOREIGN KEY (monitor_id) REFERENCES monitor (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE activities_monitors DROP FOREIGN KEY FK_233F6A7281C06096');
        $this->addSql('ALTER TABLE activities_monitors DROP FOREIGN KEY FK_233F6A724CE1C902');
        $this->addSql('DROP TABLE activities_monitors');
    }
}
