<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240215183254 extends AbstractMigration
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
        $this->addSql('DROP INDEX UNIQ_166FDEC4A76ED395 ON bureau');
        $this->addSql('ALTER TABLE bureau DROP user_id');
        $this->addSql('ALTER TABLE user CHANGE referents_id bureau_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64932516FE2 FOREIGN KEY (bureau_id) REFERENCES bureau (id)');
        $this->addSql('CREATE INDEX IDX_8D93D64932516FE2 ON user (bureau_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_referent DROP FOREIGN KEY FK_1E17486CA76ED395');
        $this->addSql('ALTER TABLE user_referent DROP FOREIGN KEY FK_1E17486C35E47E35');
        $this->addSql('DROP TABLE user_referent');
        $this->addSql('ALTER TABLE bureau ADD user_id INT DEFAULT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_166FDEC4A76ED395 ON bureau (user_id)');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64932516FE2');
        $this->addSql('DROP INDEX IDX_8D93D64932516FE2 ON user');
        $this->addSql('ALTER TABLE user CHANGE bureau_id referents_id INT DEFAULT NULL');
    }
}
