<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191116093746 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE contact_email_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE contact_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE contact_phone_number_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE contact_email (
          id INT NOT NULL, 
          value VARCHAR(255) NOT NULL, 
          label VARCHAR(255) DEFAULT NULL, 
          PRIMARY KEY(id)
        )');
        $this->addSql('CREATE TABLE contact (
          id INT NOT NULL, 
          uuid UUID NOT NULL, 
          name VARCHAR(255) NOT NULL, 
          country VARCHAR(255) DEFAULT NULL, 
          street_and_number VARCHAR(255) DEFAULT NULL, 
          postcode VARCHAR(255) DEFAULT NULL, 
          city VARCHAR(255) DEFAULT NULL, 
          created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, 
          updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, 
          PRIMARY KEY(id)
        )');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_4C62E638D17F50A6 ON contact (uuid)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_4C62E6385E237E06 ON contact (name)');
        $this->addSql('COMMENT ON COLUMN contact.uuid IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN contact.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN contact.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE contact_contact_email (
          contact_id INT NOT NULL, 
          email_id INT NOT NULL, 
          PRIMARY KEY(contact_id, email_id)
        )');
        $this->addSql('CREATE INDEX IDX_EB202FFCE7A1254A ON contact_contact_email (contact_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_EB202FFCA832C1C9 ON contact_contact_email (email_id)');
        $this->addSql('CREATE TABLE contact_contact_phone_number (
          contact_id INT NOT NULL, 
          phone_number_id INT NOT NULL, 
          PRIMARY KEY(contact_id, phone_number_id)
        )');
        $this->addSql('CREATE INDEX IDX_7A33865BE7A1254A ON contact_contact_phone_number (contact_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_7A33865B39DFD528 ON contact_contact_phone_number (phone_number_id)');
        $this->addSql('CREATE TABLE contact_phone_number (
          id INT NOT NULL, 
          value VARCHAR(255) NOT NULL, 
          label VARCHAR(255) DEFAULT NULL, 
          PRIMARY KEY(id)
        )');
        $this->addSql('ALTER TABLE 
          contact_contact_email 
        ADD 
          CONSTRAINT FK_EB202FFCE7A1254A FOREIGN KEY (contact_id) REFERENCES contact (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE 
          contact_contact_email 
        ADD 
          CONSTRAINT FK_EB202FFCA832C1C9 FOREIGN KEY (email_id) REFERENCES contact_email (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE 
          contact_contact_phone_number 
        ADD 
          CONSTRAINT FK_7A33865BE7A1254A FOREIGN KEY (contact_id) REFERENCES contact (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE 
          contact_contact_phone_number 
        ADD 
          CONSTRAINT FK_7A33865B39DFD528 FOREIGN KEY (phone_number_id) REFERENCES contact_phone_number (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA IF NOT EXISTS public');
        $this->addSql('ALTER TABLE contact_contact_email DROP CONSTRAINT FK_EB202FFCA832C1C9');
        $this->addSql('ALTER TABLE contact_contact_email DROP CONSTRAINT FK_EB202FFCE7A1254A');
        $this->addSql('ALTER TABLE contact_contact_phone_number DROP CONSTRAINT FK_7A33865BE7A1254A');
        $this->addSql('ALTER TABLE contact_contact_phone_number DROP CONSTRAINT FK_7A33865B39DFD528');
        $this->addSql('DROP SEQUENCE contact_email_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE contact_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE contact_phone_number_id_seq CASCADE');
        $this->addSql('DROP TABLE contact_email');
        $this->addSql('DROP TABLE contact');
        $this->addSql('DROP TABLE contact_contact_email');
        $this->addSql('DROP TABLE contact_contact_phone_number');
        $this->addSql('DROP TABLE contact_phone_number');
    }
}
