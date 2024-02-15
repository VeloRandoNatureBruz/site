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
    {  // Cette migration implémente la table bureau. Elle a été manuellement modifiée pour conserver les données de la BDD
        //et apporté les changements liés aux modifs des entitées .
        // this up() migration is auto-generated, please modify it to your needs

        $this->addSql('CREATE TABLE bureau (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, nom VARCHAR(50) NOT NULL, ordre INT NOT NULL, UNIQUE INDEX UNIQ_166FDEC4A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON user (email)');
        // Ajout des données à la table "Bureau"
        $this->addSql('INSERT INTO bureau (nom, ordre) SELECT nom, ordre FROM referent WHERE ordre IN (1, 2);');
        $this->addSql('INSERT INTO Bureau (nom, ordre) VALUES ("Adhérent", 99)');
        $this->addSql('UPDATE Bureau SET ordre = 3 WHERE nom = "Trésorier"');
        $this->addSql('UPDATE Bureau SET ordre = 3 WHERE nom = "Vice-Trésorier"');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649B1F79D78');
        $this->addSql('DROP INDEX IDX_8D93D649B1F79D78 ON user');
        $this->addSql('UPDATE user SET referents_id = 2 WHERE referents_id = 4');
        $this->addSql('UPDATE user SET referents_id = 99 WHERE referents_id = 3');
        $this->addSql('UPDATE user SET referents_id = 3 WHERE referents_id = 5');
        $this->addSql('UPDATE user SET referents_id = 4 WHERE referents_id = 17');
        $this->addSql('UPDATE user SET referents_id = 5 WHERE referents_id = 18');
        $this->addSql('UPDATE user SET referents_id = 6 WHERE referents_id = 19');
        $this->addSql('UPDATE user SET referents_id = 8 WHERE referents_id = 99');
        // Suppression des entrées "bureau" de la table "Referent"
        $this->addSql('DELETE FROM referent WHERE ordre IN (1, 2);');
        // Passage des Id restant au statut adhérent après migration
        $this->addSql('UPDATE user SET referents_id = 8 WHERE referents_id > 6');
        //nettoyage de la table referent
        $this->addSql("DELETE FROM referent WHERE nom = 'Adhérent'");
        $this->addSql("UPDATE referent SET ordre = 1 WHERE nom = 'Site web'");
        $this->addSql("UPDATE referent SET ordre = 2 WHERE nom = 'Rédaction'");
        $this->addSql("UPDATE referent SET ordre = 4 WHERE nom = 'Mécanique'");
        $this->addSql("UPDATE referent SET ordre = 5 WHERE nom = 'Navigation GPS'");
        $this->addSql("UPDATE referent SET ordre = 6 WHERE nom = 'Sécurité'");
        $this->addSql("UPDATE referent SET ordre = 7 WHERE nom = 'Secourisme'");
        $this->addSql("UPDATE referent SET ordre = 8 WHERE nom = 'Stagiaire'");





    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bureau DROP FOREIGN KEY FK_166FDEC4A76ED395');
        $this->addSql('DROP TABLE bureau');
        $this->addSql('DROP INDEX UNIQ_8D93D649E7927C74 ON user');
    }
}
