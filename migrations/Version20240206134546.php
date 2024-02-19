<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240206134546 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE administrator (id INT AUTO_INCREMENT NOT NULL, lastname VARCHAR(64) DEFAULT NULL, firstname VARCHAR(64) DEFAULT NULL, genre VARCHAR(8) DEFAULT NULL, email VARCHAR(128) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE authentity (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, is_verified TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_4832F981E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE employee (id INT AUTO_INCREMENT NOT NULL, lastname VARCHAR(64) NOT NULL, firstname VARCHAR(64) NOT NULL, genre VARCHAR(8) DEFAULT NULL, email VARCHAR(128) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE notice (id INT AUTO_INCREMENT NOT NULL, id_visitor_id INT NOT NULL, message VARCHAR(512) NOT NULL, INDEX IDX_480D45C2A2816EBA (id_visitor_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE photo (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(64) NOT NULL, url VARCHAR(256) DEFAULT NULL, photo LONGBLOB NOT NULL, date_creation DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE service (id INT AUTO_INCREMENT NOT NULL, services LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', date_creation DATETIME NOT NULL, date_modification DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE timetable (id INT AUTO_INCREMENT NOT NULL, day VARCHAR(64) DEFAULT NULL, date DATE DEFAULT NULL, timetable LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vehicule (id INT AUTO_INCREMENT NOT NULL, photo_id INT DEFAULT NULL, brandt VARCHAR(64) DEFAULT NULL, model VARCHAR(64) DEFAULT NULL, features LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', year DATE DEFAULT NULL, kilometers INT DEFAULT NULL, type VARCHAR(64) DEFAULT NULL, price DOUBLE PRECISION DEFAULT NULL, UNIQUE INDEX UNIQ_292FFF1D7E9E4C8C (photo_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE visitor (id INT AUTO_INCREMENT NOT NULL, lastname VARCHAR(64) NOT NULL, firstname VARCHAR(64) NOT NULL, email VARCHAR(128) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE notice ADD CONSTRAINT FK_480D45C2A2816EBA FOREIGN KEY (id_visitor_id) REFERENCES visitor (id)');
        $this->addSql('ALTER TABLE vehicule ADD CONSTRAINT FK_292FFF1D7E9E4C8C FOREIGN KEY (photo_id) REFERENCES photo (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE notice DROP FOREIGN KEY FK_480D45C2A2816EBA');
        $this->addSql('ALTER TABLE vehicule DROP FOREIGN KEY FK_292FFF1D7E9E4C8C');
        $this->addSql('DROP TABLE administrator');
        $this->addSql('DROP TABLE authentity');
        $this->addSql('DROP TABLE employee');
        $this->addSql('DROP TABLE notice');
        $this->addSql('DROP TABLE photo');
        $this->addSql('DROP TABLE service');
        $this->addSql('DROP TABLE timetable');
        $this->addSql('DROP TABLE vehicule');
        $this->addSql('DROP TABLE visitor');
    }
}
