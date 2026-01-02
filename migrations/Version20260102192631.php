<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260102192631 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE visit_read (id VARCHAR(36) NOT NULL, listing_id VARCHAR(36) NOT NULL, agent VARCHAR(255) NOT NULL, visitor_name VARCHAR(255) NOT NULL, visitor_email VARCHAR(255) NOT NULL, visitor_phone VARCHAR(20) NOT NULL, visit_start_time TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, visit_end_time TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY (id))');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE visit_read');
    }
}
