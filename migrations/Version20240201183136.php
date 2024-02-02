<?php

declare(strict_types=1);

namespace migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240201183136 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE activite (id INT AUTO_INCREMENT NOT NULL, etat_id INT DEFAULT NULL, lieu_id INT DEFAULT NULL, organisateur_id INT DEFAULT NULL, categories_formation_id INT NOT NULL, nom VARCHAR(255) NOT NULL, date_activite DATETIME NOT NULL, duree INT DEFAULT NULL, distance INT DEFAULT NULL, infos_activite VARCHAR(1000) DEFAULT NULL, denivele INT DEFAULT NULL, difficulte INT DEFAULT NULL, url_album_photo VARCHAR(255) DEFAULT NULL, url_album_photo_deux VARCHAR(255) DEFAULT NULL, pdf VARCHAR(255) DEFAULT NULL, pdf_modification VARCHAR(255) DEFAULT NULL, total_participant INT DEFAULT NULL, INDEX IDX_B8755515D5E86FF (etat_id), INDEX IDX_B87555156AB213CC (lieu_id), INDEX IDX_B8755515D936B2FA (organisateur_id), INDEX IDX_B8755515EC054238 (categories_formation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE activite_content (id INT AUTO_INCREMENT NOT NULL, balade_text LONGTEXT DEFAULT NULL, escapade_text LONGTEXT DEFAULT NULL, mecanique_text LONGTEXT DEFAULT NULL, securite_text LONGTEXT DEFAULT NULL, secourisme_text LONGTEXT DEFAULT NULL, photo_video_text LONGTEXT DEFAULT NULL, projection_film_text LONGTEXT DEFAULT NULL, autre_text LONGTEXT DEFAULT NULL, balade_photo VARCHAR(255) DEFAULT NULL, escapade_photo VARCHAR(255) DEFAULT NULL, mecanique_photo VARCHAR(255) DEFAULT NULL, securite_photo VARCHAR(255) DEFAULT NULL, secourisme_photo VARCHAR(255) DEFAULT NULL, photo_video_photo VARCHAR(255) DEFAULT NULL, projection_film_photo VARCHAR(255) DEFAULT NULL, ecocitoyennete_text LONGTEXT DEFAULT NULL, ecocitoyennete_photo VARCHAR(255) DEFAULT NULL, autre_photo VARCHAR(255) DEFAULT NULL, formation_text_intro LONGTEXT DEFAULT NULL, randovelo_text_intro LONGTEXT DEFAULT NULL, projectionfilm_text_intro LONGTEXT DEFAULT NULL, ecocitoyennete_text_intro LONGTEXT DEFAULT NULL, autres_text_intro LONGTEXT DEFAULT NULL, balade_title VARCHAR(100) DEFAULT NULL, escapade_title VARCHAR(100) DEFAULT NULL, mecanique_title VARCHAR(100) DEFAULT NULL, securite_title VARCHAR(100) DEFAULT NULL, secourisme_title VARCHAR(100) DEFAULT NULL, photo_video_title VARCHAR(100) DEFAULT NULL, projection_film_title VARCHAR(100) DEFAULT NULL, autre_title VARCHAR(100) DEFAULT NULL, ecocitoyennete_title VARCHAR(100) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE actualite (id INT AUTO_INCREMENT NOT NULL, actu VARCHAR(255) NOT NULL, date_actu DATETIME NOT NULL, affiche_actu TINYINT(1) NOT NULL, url LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categorie (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categorie_formation (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commentaire (id INT AUTO_INCREMENT NOT NULL, documentation_id INT DEFAULT NULL, user_name VARCHAR(255) NOT NULL, date_creation DATETIME NOT NULL, date_modification DATETIME DEFAULT NULL, commentaire LONGTEXT NOT NULL, INDEX IDX_67F068BCC703EEC9 (documentation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE doc_pdf (id INT AUTO_INCREMENT NOT NULL, pdfactivite_id INT DEFAULT NULL, nompdf VARCHAR(255) NOT NULL, INDEX IDX_A77A1AF0C2798AFF (pdfactivite_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE documentation (id INT AUTO_INCREMENT NOT NULL, categorie_id INT DEFAULT NULL, date_creation DATETIME NOT NULL, date_modifier DATETIME DEFAULT NULL, auteur VARCHAR(255) NOT NULL, titre VARCHAR(255) NOT NULL, intro LONGTEXT NOT NULL, paragraphe1 LONGTEXT DEFAULT NULL, paragraphe2 LONGTEXT DEFAULT NULL, image VARCHAR(255) DEFAULT NULL, image_modification VARCHAR(255) DEFAULT NULL, image_legende VARCHAR(255) DEFAULT NULL, image2 VARCHAR(255) DEFAULT NULL, image_modification2 VARCHAR(255) DEFAULT NULL, image_legende2 VARCHAR(255) DEFAULT NULL, url LONGTEXT DEFAULT NULL, pdf VARCHAR(255) DEFAULT NULL, pdf_modification VARCHAR(255) DEFAULT NULL, INDEX IDX_73D5A93BBCF5E72D (categorie_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE etat (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE etiquette_content (id INT AUTO_INCREMENT NOT NULL, first_etiquette_text VARCHAR(255) DEFAULT NULL, first_etiquette_photo VARCHAR(255) DEFAULT NULL, second_etiquette_text VARCHAR(255) DEFAULT NULL, second_etiquette_photo VARCHAR(255) DEFAULT NULL, third_etiquette_text VARCHAR(255) DEFAULT NULL, third_etiquette_photo VARCHAR(255) DEFAULT NULL, fourth_etiquette_text VARCHAR(255) DEFAULT NULL, fourth_etiquette_photo VARCHAR(255) DEFAULT NULL, first_etiquette_overlay VARCHAR(255) DEFAULT NULL, second_etiquette_overlay VARCHAR(255) DEFAULT NULL, third_etiquette_overlay VARCHAR(255) DEFAULT NULL, fourth_etiquette_overlay VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE intro_photo (id INT AUTO_INCREMENT NOT NULL, presentation_photo_intro VARCHAR(255) DEFAULT NULL, organisation_photo_intro VARCHAR(255) DEFAULT NULL, rando_velo_photo_intro VARCHAR(255) DEFAULT NULL, formation_photo_intro VARCHAR(255) DEFAULT NULL, projection_film_photo_intro VARCHAR(255) DEFAULT NULL, ecocitoyennete_photo_intro VARCHAR(255) DEFAULT NULL, autre_photo_intro VARCHAR(255) DEFAULT NULL, programme_photo_intro VARCHAR(255) DEFAULT NULL, album_photo_photo_intro VARCHAR(255) DEFAULT NULL, trombi_photo_intro VARCHAR(255) DEFAULT NULL, profil_photo_intro VARCHAR(255) DEFAULT NULL, documentation_photo_intro VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE lieu (id INT AUTO_INCREMENT NOT NULL, nom_ville VARCHAR(255) NOT NULL, cp_ville VARCHAR(255) DEFAULT NULL, num_rue VARCHAR(255) DEFAULT NULL, nom_rue VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE partenaire (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, logo VARCHAR(255) NOT NULL, url LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE photo (id INT AUTO_INCREMENT NOT NULL, adhherent_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_14B78418ED3E777B (adhherent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE photo_album (id INT AUTO_INCREMENT NOT NULL, activite_id INT DEFAULT NULL, image VARCHAR(255) DEFAULT NULL, url LONGTEXT DEFAULT NULL, INDEX IDX_83C969F49B0F88B1 (activite_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE photo_carousel (id INT AUTO_INCREMENT NOT NULL, image1 VARCHAR(255) DEFAULT NULL, image2 VARCHAR(255) DEFAULT NULL, image3 VARCHAR(255) DEFAULT NULL, image4 VARCHAR(255) DEFAULT NULL, image5 VARCHAR(255) DEFAULT NULL, image6 VARCHAR(255) DEFAULT NULL, image7 VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE referent (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(75) NOT NULL, ordre INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reset_password_request (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, selector VARCHAR(20) NOT NULL, hashed_token VARCHAR(100) NOT NULL, requested_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', expires_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_7CE748AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE text_accueil (id INT AUTO_INCREMENT NOT NULL, first_text VARCHAR(255) DEFAULT NULL, second_text VARCHAR(255) DEFAULT NULL, third_text VARCHAR(255) DEFAULT NULL, fourth_text VARCHAR(255) DEFAULT NULL, fifth_text VARCHAR(255) DEFAULT NULL, sixth_text VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE text_presentation (id INT AUTO_INCREMENT NOT NULL, text_one LONGTEXT DEFAULT NULL, text_two LONGTEXT DEFAULT NULL, text_three LONGTEXT DEFAULT NULL, text_four LONGTEXT DEFAULT NULL, text_five LONGTEXT DEFAULT NULL, text_six LONGTEXT DEFAULT NULL, title_one VARCHAR(255) DEFAULT NULL, title_two VARCHAR(255) DEFAULT NULL, title_three VARCHAR(255) DEFAULT NULL, title_four VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, referents_id INT DEFAULT NULL, username VARCHAR(50) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, nom VARCHAR(60) NOT NULL, prenom VARCHAR(60) NOT NULL, telephone VARCHAR(20) DEFAULT NULL, email VARCHAR(255) NOT NULL, date_naissance DATETIME DEFAULT NULL, reset_token VARCHAR(100) DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), INDEX IDX_8D93D649B1F79D78 (referents_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_activite (user_id INT NOT NULL, activite_id INT NOT NULL, INDEX IDX_58F8B115A76ED395 (user_id), INDEX IDX_58F8B1159B0F88B1 (activite_id), PRIMARY KEY(user_id, activite_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE activite ADD CONSTRAINT FK_B8755515D5E86FF FOREIGN KEY (etat_id) REFERENCES etat (id)');
        $this->addSql('ALTER TABLE activite ADD CONSTRAINT FK_B87555156AB213CC FOREIGN KEY (lieu_id) REFERENCES lieu (id)');
        $this->addSql('ALTER TABLE activite ADD CONSTRAINT FK_B8755515D936B2FA FOREIGN KEY (organisateur_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE activite ADD CONSTRAINT FK_B8755515EC054238 FOREIGN KEY (categories_formation_id) REFERENCES categorie_formation (id)');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BCC703EEC9 FOREIGN KEY (documentation_id) REFERENCES documentation (id)');
        $this->addSql('ALTER TABLE doc_pdf ADD CONSTRAINT FK_A77A1AF0C2798AFF FOREIGN KEY (pdfactivite_id) REFERENCES activite (id)');
        $this->addSql('ALTER TABLE documentation ADD CONSTRAINT FK_73D5A93BBCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id)');
        $this->addSql('ALTER TABLE photo ADD CONSTRAINT FK_14B78418ED3E777B FOREIGN KEY (adhherent_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE photo_album ADD CONSTRAINT FK_83C969F49B0F88B1 FOREIGN KEY (activite_id) REFERENCES activite (id)');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649B1F79D78 FOREIGN KEY (referents_id) REFERENCES referent (id)');
        $this->addSql('ALTER TABLE user_activite ADD CONSTRAINT FK_58F8B115A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_activite ADD CONSTRAINT FK_58F8B1159B0F88B1 FOREIGN KEY (activite_id) REFERENCES activite (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE activite DROP FOREIGN KEY FK_B8755515D5E86FF');
        $this->addSql('ALTER TABLE activite DROP FOREIGN KEY FK_B87555156AB213CC');
        $this->addSql('ALTER TABLE activite DROP FOREIGN KEY FK_B8755515D936B2FA');
        $this->addSql('ALTER TABLE activite DROP FOREIGN KEY FK_B8755515EC054238');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BCC703EEC9');
        $this->addSql('ALTER TABLE doc_pdf DROP FOREIGN KEY FK_A77A1AF0C2798AFF');
        $this->addSql('ALTER TABLE documentation DROP FOREIGN KEY FK_73D5A93BBCF5E72D');
        $this->addSql('ALTER TABLE photo DROP FOREIGN KEY FK_14B78418ED3E777B');
        $this->addSql('ALTER TABLE photo_album DROP FOREIGN KEY FK_83C969F49B0F88B1');
        $this->addSql('ALTER TABLE reset_password_request DROP FOREIGN KEY FK_7CE748AA76ED395');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649B1F79D78');
        $this->addSql('ALTER TABLE user_activite DROP FOREIGN KEY FK_58F8B115A76ED395');
        $this->addSql('ALTER TABLE user_activite DROP FOREIGN KEY FK_58F8B1159B0F88B1');
        $this->addSql('DROP TABLE activite');
        $this->addSql('DROP TABLE activite_content');
        $this->addSql('DROP TABLE actualite');
        $this->addSql('DROP TABLE categorie');
        $this->addSql('DROP TABLE categorie_formation');
        $this->addSql('DROP TABLE commentaire');
        $this->addSql('DROP TABLE doc_pdf');
        $this->addSql('DROP TABLE documentation');
        $this->addSql('DROP TABLE etat');
        $this->addSql('DROP TABLE etiquette_content');
        $this->addSql('DROP TABLE intro_photo');
        $this->addSql('DROP TABLE lieu');
        $this->addSql('DROP TABLE partenaire');
        $this->addSql('DROP TABLE photo');
        $this->addSql('DROP TABLE photo_album');
        $this->addSql('DROP TABLE photo_carousel');
        $this->addSql('DROP TABLE referent');
        $this->addSql('DROP TABLE reset_password_request');
        $this->addSql('DROP TABLE text_accueil');
        $this->addSql('DROP TABLE text_presentation');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_activite');
    }
}
