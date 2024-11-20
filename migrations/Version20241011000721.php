<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20241011000721 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create member, fruit, and panier tables with foreign key constraints';
    }

    public function up(Schema $schema): void
    {
        // Create the member table
        $this->addSql('
            CREATE TABLE IF NOT EXISTS member (
                id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
                email VARCHAR(180) NOT NULL
            )
        ');

        // Create the fruit table
        $this->addSql('
            CREATE TABLE IF NOT EXISTS fruit (
                id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
                nom VARCHAR(255) NOT NULL,
                couleur VARCHAR(255) NOT NULL,
                quantite INTEGER NOT NULL
            )
        ');

        // Drop the existing panier table if it exists
        $this->addSql('DROP TABLE IF EXISTS panier');

        // Create the panier table with a foreign key reference to the member table
        $this->addSql('
            CREATE TABLE panier (
                id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
                nom VARCHAR(255) NOT NULL,
                creator_id INTEGER NOT NULL,
                CONSTRAINT FK_CREATOR FOREIGN KEY (creator_id) REFERENCES member (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
            )
        ');

        // Create the messenger_messages table
        $this->addSql('
            CREATE TABLE IF NOT EXISTS messenger_messages (
                id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
                body CLOB NOT NULL,
                headers CLOB NOT NULL,
                queue_name VARCHAR(190) NOT NULL,
                created_at DATETIME NOT NULL,
                available_at DATETIME NOT NULL,
                delivered_at DATETIME DEFAULT NULL
            )
        ');

        // Create indexes for the messenger_messages table
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)');
        $this->addSql('CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)');
        $this->addSql('CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)');
    }

    public function down(Schema $schema): void
    {
        // Drop the tables in reverse order
        $this->addSql('DROP TABLE IF EXISTS messenger_messages');
        $this->addSql('DROP TABLE IF EXISTS panier');
        $this->addSql('DROP TABLE IF EXISTS fruit');
        $this->addSql('DROP TABLE IF EXISTS member');
    }
}
