<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191116220754 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP INDEX uniq_eb202ffca832c1c9');
        $this->addSql('CREATE INDEX IDX_EB202FFCA832C1C9 ON contact_contact_email (email_id)');
        $this->addSql('DROP INDEX uniq_7a33865b39dfd528');
        $this->addSql('CREATE INDEX IDX_7A33865B39DFD528 ON contact_contact_phone_number (phone_number_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA IF NOT EXISTS public');
        $this->addSql('DROP INDEX IDX_EB202FFCA832C1C9');
        $this->addSql('CREATE UNIQUE INDEX uniq_eb202ffca832c1c9 ON contact_contact_email (email_id)');
        $this->addSql('DROP INDEX IDX_7A33865B39DFD528');
        $this->addSql('CREATE UNIQUE INDEX uniq_7a33865b39dfd528 ON contact_contact_phone_number (phone_number_id)');
    }
}
