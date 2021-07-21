<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210721100253 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment_hotel ADD hotel_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE comment_hotel ADD CONSTRAINT FK_AAC9C6283243BB18 FOREIGN KEY (hotel_id) REFERENCES hotel (id)');
        $this->addSql('CREATE INDEX IDX_AAC9C6283243BB18 ON comment_hotel (hotel_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment_hotel DROP FOREIGN KEY FK_AAC9C6283243BB18');
        $this->addSql('DROP INDEX IDX_AAC9C6283243BB18 ON comment_hotel');
        $this->addSql('ALTER TABLE comment_hotel DROP hotel_id');
    }
}
