<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190628153143 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP SEQUENCE categories_fields_values_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE account_fields_values_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE account_fields_values (id INT NOT NULL, field_id INT DEFAULT NULL, account_id INT DEFAULT NULL, value VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1C4FC56F443707B0 ON account_fields_values (field_id)');
        $this->addSql('CREATE INDEX IDX_1C4FC56F9B6B5FBA ON account_fields_values (account_id)');
        $this->addSql('ALTER TABLE account_fields_values ADD CONSTRAINT FK_1C4FC56F443707B0 FOREIGN KEY (field_id) REFERENCES fields (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE account_fields_values ADD CONSTRAINT FK_1C4FC56F9B6B5FBA FOREIGN KEY (account_id) REFERENCES accounts (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('DROP TABLE categories_fields_values');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE account_fields_values_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE categories_fields_values_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE categories_fields_values (id INT NOT NULL, field_id INT DEFAULT NULL, category_id INT DEFAULT NULL, value VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_770349e712469de2 ON categories_fields_values (category_id)');
        $this->addSql('CREATE UNIQUE INDEX uniq_770349e7443707b0 ON categories_fields_values (field_id)');
        $this->addSql('ALTER TABLE categories_fields_values ADD CONSTRAINT fk_770349e7443707b0 FOREIGN KEY (field_id) REFERENCES fields (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE categories_fields_values ADD CONSTRAINT fk_770349e712469de2 FOREIGN KEY (category_id) REFERENCES categories (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('DROP TABLE account_fields_values');
    }
}
