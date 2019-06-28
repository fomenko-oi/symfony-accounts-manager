<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190623180811 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE categories_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE accounts_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE fields_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE categories (id INT NOT NULL, parent_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_3AF34668727ACA70 ON categories (parent_id)');
        $this->addSql('CREATE TABLE categories_fields (category_id INT NOT NULL, field_id INT NOT NULL, PRIMARY KEY(category_id, field_id))');
        $this->addSql('CREATE INDEX IDX_768CDDB012469DE2 ON categories_fields (category_id)');
        $this->addSql('CREATE INDEX IDX_768CDDB0443707B0 ON categories_fields (field_id)');
        $this->addSql('CREATE TABLE accounts (id INT NOT NULL, category_id INT NOT NULL, login VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_CAC89EAC12469DE2 ON accounts (category_id)');
        $this->addSql('CREATE TABLE fields (id INT NOT NULL, name VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, variables JSON DEFAULT NULL, required BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE categories ADD CONSTRAINT FK_3AF34668727ACA70 FOREIGN KEY (parent_id) REFERENCES categories (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE categories_fields ADD CONSTRAINT FK_768CDDB012469DE2 FOREIGN KEY (category_id) REFERENCES categories (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE categories_fields ADD CONSTRAINT FK_768CDDB0443707B0 FOREIGN KEY (field_id) REFERENCES fields (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE accounts ADD CONSTRAINT FK_CAC89EAC12469DE2 FOREIGN KEY (category_id) REFERENCES categories (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE categories DROP CONSTRAINT FK_3AF34668727ACA70');
        $this->addSql('ALTER TABLE categories_fields DROP CONSTRAINT FK_768CDDB012469DE2');
        $this->addSql('ALTER TABLE accounts DROP CONSTRAINT FK_CAC89EAC12469DE2');
        $this->addSql('ALTER TABLE categories_fields DROP CONSTRAINT FK_768CDDB0443707B0');
        $this->addSql('DROP SEQUENCE categories_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE accounts_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE fields_id_seq CASCADE');
        $this->addSql('DROP TABLE categories');
        $this->addSql('DROP TABLE categories_fields');
        $this->addSql('DROP TABLE accounts');
        $this->addSql('DROP TABLE fields');
    }
}
