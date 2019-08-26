<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190826213347 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE news_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE sources_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE news_files_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE news (id INT NOT NULL, source_id INT DEFAULT NULL, date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, publicated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, link VARCHAR(255) NOT NULL, eid VARCHAR(255) NOT NULL, content_title VARCHAR(255) NOT NULL, content_description TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_1DD39950953C1C61 ON news (source_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1DD3995036AC99F1 ON news (link)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1DD399504FBDA576 ON news (eid)');
        $this->addSql('CREATE TABLE sources (id INT NOT NULL, name VARCHAR(255) NOT NULL, rss_url VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE news_files (id INT NOT NULL, mews_id INT NOT NULL, date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, info_path VARCHAR(255) NOT NULL, info_name VARCHAR(255) NOT NULL, info_size INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_7C8AC707844C433D ON news_files (mews_id)');
        $this->addSql('CREATE INDEX IDX_7C8AC707AA9E377A ON news_files (date)');
        $this->addSql('COMMENT ON COLUMN news_files.date IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE news ADD CONSTRAINT FK_1DD39950953C1C61 FOREIGN KEY (source_id) REFERENCES sources (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE news_files ADD CONSTRAINT FK_7C8AC707844C433D FOREIGN KEY (mews_id) REFERENCES news (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE news_files DROP CONSTRAINT FK_7C8AC707844C433D');
        $this->addSql('ALTER TABLE news DROP CONSTRAINT FK_1DD39950953C1C61');
        $this->addSql('DROP SEQUENCE news_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE sources_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE news_files_id_seq CASCADE');
        $this->addSql('DROP TABLE news');
        $this->addSql('DROP TABLE sources');
        $this->addSql('DROP TABLE news_files');
    }
}
