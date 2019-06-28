<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190628132319 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE category_field_value (category_id INT NOT NULL, field_value_id INT NOT NULL, PRIMARY KEY(category_id, field_value_id))');
        $this->addSql('CREATE INDEX IDX_814B8CA412469DE2 ON category_field_value (category_id)');
        $this->addSql('CREATE INDEX IDX_814B8CA42F183C6F ON category_field_value (field_value_id)');
        $this->addSql('CREATE TABLE categories_fields_values (id INT NOT NULL, field_id INT DEFAULT NULL, category_id INT DEFAULT NULL, value VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_770349E7443707B0 ON categories_fields_values (field_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_770349E712469DE2 ON categories_fields_values (category_id)');
        $this->addSql('ALTER TABLE category_field_value ADD CONSTRAINT FK_814B8CA412469DE2 FOREIGN KEY (category_id) REFERENCES categories (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE category_field_value ADD CONSTRAINT FK_814B8CA42F183C6F FOREIGN KEY (field_value_id) REFERENCES categories_fields_values (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE categories_fields_values ADD CONSTRAINT FK_770349E7443707B0 FOREIGN KEY (field_id) REFERENCES fields (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE categories_fields_values ADD CONSTRAINT FK_770349E712469DE2 FOREIGN KEY (category_id) REFERENCES categories (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE category_field_value DROP CONSTRAINT FK_814B8CA42F183C6F');
        $this->addSql('DROP TABLE category_field_value');
        $this->addSql('DROP TABLE categories_fields_values');
    }
}
