<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20241001103443 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add `person` table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE SEQUENCE person_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE person (
          id INT NOT NULL,
          last_name VARCHAR(50) NOT NULL,
          first_name VARCHAR(50) NOT NULL,
          avatar VARCHAR(255) NOT NULL,
          bio TEXT NOT NULL,
          profile_link VARCHAR(255) NOT NULL,
          is_bookmarked BOOLEAN NOT NULL,
          PRIMARY KEY(id)
        )');
        $this->addSql('CREATE TABLE messenger_messages (
          id BIGSERIAL NOT NULL,
          body TEXT NOT NULL,
          headers TEXT NOT NULL,
          queue_name VARCHAR(190) NOT NULL,
          created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
          available_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
          delivered_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL,
          PRIMARY KEY(id)
        )');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)');
        $this->addSql('CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)');
        $this->addSql('CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)');
        $this->addSql('COMMENT ON COLUMN messenger_messages.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN messenger_messages.available_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN messenger_messages.delivered_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE
        OR REPLACE FUNCTION notify_messenger_messages() RETURNS TRIGGER AS $$ BEGIN PERFORM pg_notify(
          \'messenger_messages\', NEW.queue_name :: text
        );
        RETURN NEW;
        END;
        $$ LANGUAGE plpgsql;');
        $this->addSql('DROP TRIGGER IF EXISTS notify_trigger ON messenger_messages;');
        $this->addSql('CREATE TRIGGER notify_trigger AFTER INSERT
        OR
        UPDATE
          ON messenger_messages FOR EACH ROW EXECUTE PROCEDURE notify_messenger_messages();');

        $this->addSql("
        INSERT INTO person (id, first_name, last_name, avatar, bio, profile_link, is_bookmarked) 
        VALUES 
        (1, 'fynn', 'somo', 'https://images.unsplash.com/photo-1568602471122-7832951cc4c5?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=facearea&facepad=2&w=300&h=300&q=80', 'Musicien de jazz, Fynn enchante son public avec des compositions originales mêlant improvisation et mélodie.', '#', true),
        (2, 'eva', 'rall', 'https://images.unsplash.com/photo-1722270608841-35d7372a2e85?q=80&auto=format&fit=facearea&facepad=2&w=300&h=300&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D', 'Romancière à succès, Eva explore les relations humaines à travers des récits poignants et intimes.', '#', true),
        (3, 'drew', 'soppr', 'https://images.unsplash.com/photo-1623582854588-d60de57fa33f?q=80&auto=format&fit=facearea&facepad=2&w=300&h=300&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D', 'Cinéaste indépendant, Drew réalise des documentaires qui capturent les histoires méconnues de personnes ordinaires.', '#', true);
    ");
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE person_id_seq CASCADE');
        $this->addSql('DROP TABLE person');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
