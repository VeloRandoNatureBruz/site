<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240207150346 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_referent (user_id INT NOT NULL, referent_id INT NOT NULL, INDEX IDX_1E17486CA76ED395 (user_id), INDEX IDX_1E17486C35E47E35 (referent_id), PRIMARY KEY(user_id, referent_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_referent ADD CONSTRAINT FK_1E17486CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_referent ADD CONSTRAINT FK_1E17486C35E47E35 FOREIGN KEY (referent_id) REFERENCES referent (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649B1F79D78');
        $this->addSql('DROP INDEX IDX_8D93D649B1F79D78 ON user');
        $this->addSql('ALTER TABLE user DROP referents_id');
        // Ajout des données à la table "Bureau"
        $this->addSql('INSERT INTO bureau (nom, ordre) SELECT nom, ordre FROM referent WHERE ordre IN (1, 2, 4);');
        // Suppression des entrées sélectionnées de la table "Referent"
        //$this->addSql('DELETE FROM referent WHERE ordre IN (1, 2, 4);');

    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_referent DROP FOREIGN KEY FK_1E17486CA76ED395');
        $this->addSql('ALTER TABLE user_referent DROP FOREIGN KEY FK_1E17486C35E47E35');
        $this->addSql('DROP TABLE user_referent');
        $this->addSql('ALTER TABLE user ADD referents_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649B1F79D78 FOREIGN KEY (referents_id) REFERENCES referent (id)');
        $this->addSql('CREATE INDEX IDX_8D93D649B1F79D78 ON user (referents_id)');
    }
}
