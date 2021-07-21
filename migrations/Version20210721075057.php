<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210721075057 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE blog (id INT AUTO_INCREMENT NOT NULL, picture VARCHAR(255) DEFAULT NULL, short_description LONGTEXT DEFAULT NULL, contenu LONGTEXT DEFAULT NULL, date DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE comment_hotel (id INT AUTO_INCREMENT NOT NULL, hotel_id INT DEFAULT NULL, comment LONGTEXT DEFAULT NULL, comment_date DATETIME DEFAULT NULL, INDEX IDX_AAC9C6283243BB18 (hotel_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE comment_restaurant (id INT AUTO_INCREMENT NOT NULL, restaurant_id INT DEFAULT NULL, comment LONGTEXT DEFAULT NULL, comment_date DATETIME DEFAULT NULL, INDEX IDX_6974981FB1E7706E (restaurant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hotel (id INT AUTO_INCREMENT NOT NULL, picture VARCHAR(255) DEFAULT NULL, short_description LONGTEXT DEFAULT NULL, contenu LONGTEXT DEFAULT NULL, date DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, lieu VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE restaurant (id INT AUTO_INCREMENT NOT NULL, picture VARCHAR(255) DEFAULT NULL, short_description LONGTEXT DEFAULT NULL, contenu LONGTEXT DEFAULT NULL, date DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE comment_hotel ADD CONSTRAINT FK_AAC9C6283243BB18 FOREIGN KEY (hotel_id) REFERENCES hotel (id)');
        $this->addSql('ALTER TABLE comment_restaurant ADD CONSTRAINT FK_6974981FB1E7706E FOREIGN KEY (restaurant_id) REFERENCES restaurant (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment_hotel DROP FOREIGN KEY FK_AAC9C6283243BB18');
        $this->addSql('ALTER TABLE comment_restaurant DROP FOREIGN KEY FK_6974981FB1E7706E');
        $this->addSql('DROP TABLE blog');
        $this->addSql('DROP TABLE comment_hotel');
        $this->addSql('DROP TABLE comment_restaurant');
        $this->addSql('DROP TABLE hotel');
        $this->addSql('DROP TABLE restaurant');
    }
}
