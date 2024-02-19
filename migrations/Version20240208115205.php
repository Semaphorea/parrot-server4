<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240208115205 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE notice DROP FOREIGN KEY FK_480D45C2A2816EBA');
        $this->addSql('DROP INDEX IDX_480D45C2A2816EBA ON notice');
        $this->addSql('ALTER TABLE notice DROP id_visitor_id');
        $this->addSql('ALTER TABLE notice ADD CONSTRAINT FK_480D45C2BF396750 FOREIGN KEY (id) REFERENCES visitor (id)');
        $this->addSql('ALTER TABLE service CHANGE services services JSON NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE notice DROP FOREIGN KEY FK_480D45C2BF396750');
        $this->addSql('ALTER TABLE notice ADD id_visitor_id INT NOT NULL');
        $this->addSql('ALTER TABLE notice ADD CONSTRAINT FK_480D45C2A2816EBA FOREIGN KEY (id_visitor_id) REFERENCES visitor (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_480D45C2A2816EBA ON notice (id_visitor_id)');
        $this->addSql('ALTER TABLE service CHANGE services services LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\'');
    }
}
