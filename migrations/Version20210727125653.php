<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210727125653 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment_hotel ADD auteur_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE comment_hotel ADD CONSTRAINT FK_AAC9C62860BB6FE6 FOREIGN KEY (auteur_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_AAC9C62860BB6FE6 ON comment_hotel (auteur_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment_hotel DROP FOREIGN KEY FK_AAC9C62860BB6FE6');
        $this->addSql('DROP INDEX IDX_AAC9C62860BB6FE6 ON comment_hotel');
        $this->addSql('ALTER TABLE comment_hotel DROP auteur_id');
    }
}
