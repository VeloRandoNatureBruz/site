<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240207113256 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        //cette première ligne suprime un doublon en BDD
        $this->addSql('DELETE FROM user WHERE id = 105');
        $this->addSql('CREATE TABLE bureau (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, nom VARCHAR(50) NOT NULL, ordre INT NOT NULL, UNIQUE INDEX UNIQ_166FDEC4A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON user (email)');
        // Ajout des données à la table "Bureau"
        $this->addSql('INSERT INTO bureau (nom, ordre) SELECT nom, ordre FROM referent WHERE ordre IN (1, 2);');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649B1F79D78');
        $this->addSql('DROP INDEX IDX_8D93D649B1F79D78 ON user');
        $this->addSql('UPDATE user SET referents_id = 2 WHERE referents_id = 4');
        $this->addSql('UPDATE user SET referents_id = 3 WHERE referents_id = 5');
        $this->addSql('UPDATE user SET referents_id = 4 WHERE referents_id = 17');
        $this->addSql('UPDATE user SET referents_id = 5 WHERE referents_id = 18');
        $this->addSql('UPDATE user SET referents_id = 6 WHERE referents_id = 19');
        // Suppression des entrées "bureau" de la table "Referent"
        $this->addSql('DELETE FROM referent WHERE ordre IN (1, 2);');
        $this->addSql('UPDATE user SET referents_id = NULL WHERE referents_id > 6');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bureau DROP FOREIGN KEY FK_166FDEC4A76ED395');
        $this->addSql('DROP TABLE bureau');
        $this->addSql('DROP INDEX UNIQ_8D93D649E7927C74 ON user');
    }
}
