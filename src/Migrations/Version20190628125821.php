<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190628125821 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE field_value_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE categories_fields_values (category_id INT NOT NULL, field_id INT NOT NULL, PRIMARY KEY(category_id, field_id))');
        $this->addSql('CREATE INDEX IDX_770349E712469DE2 ON categories_fields_values (category_id)');
        $this->addSql('CREATE INDEX IDX_770349E7443707B0 ON categories_fields_values (field_id)');
        $this->addSql('CREATE TABLE field_value (id INT NOT NULL, field_id INT DEFAULT NULL, value VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_36D0CECF443707B0 ON field_value (field_id)');
        $this->addSql('ALTER TABLE categories_fields_values ADD CONSTRAINT FK_770349E712469DE2 FOREIGN KEY (category_id) REFERENCES categories (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE categories_fields_values ADD CONSTRAINT FK_770349E7443707B0 FOREIGN KEY (field_id) REFERENCES field_value (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE field_value ADD CONSTRAINT FK_36D0CECF443707B0 FOREIGN KEY (field_id) REFERENCES fields (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE categories_fields_values DROP CONSTRAINT FK_770349E7443707B0');
        $this->addSql('DROP SEQUENCE field_value_id_seq CASCADE');
        $this->addSql('DROP TABLE categories_fields_values');
        $this->addSql('DROP TABLE field_value');
    }
}
