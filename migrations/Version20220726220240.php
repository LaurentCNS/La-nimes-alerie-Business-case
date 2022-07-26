<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220726220240 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE produit_client (produit_id INT NOT NULL, client_id INT NOT NULL, INDEX IDX_3016CF6FF347EFB (produit_id), INDEX IDX_3016CF6F19EB6921 (client_id), PRIMARY KEY(produit_id, client_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE produit_client ADD CONSTRAINT FK_3016CF6FF347EFB FOREIGN KEY (produit_id) REFERENCES produit (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE produit_client ADD CONSTRAINT FK_3016CF6F19EB6921 FOREIGN KEY (client_id) REFERENCES client (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE avis ADD produit_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE avis ADD CONSTRAINT FK_8F91ABF0F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
        $this->addSql('CREATE INDEX IDX_8F91ABF0F347EFB ON avis (produit_id)');
        $this->addSql('ALTER TABLE ligne ADD produit_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE ligne ADD CONSTRAINT FK_57F0DB83F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
        $this->addSql('CREATE INDEX IDX_57F0DB83F347EFB ON ligne (produit_id)');
        $this->addSql('ALTER TABLE photo ADD produit_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE photo ADD CONSTRAINT FK_14B78418F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
        $this->addSql('CREATE INDEX IDX_14B78418F347EFB ON photo (produit_id)');
        $this->addSql('ALTER TABLE produit ADD promotion_id INT DEFAULT NULL, ADD categorie_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC27139DF194 FOREIGN KEY (promotion_id) REFERENCES promotion (id)');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC27BCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id)');
        $this->addSql('CREATE INDEX IDX_29A5EC27139DF194 ON produit (promotion_id)');
        $this->addSql('CREATE INDEX IDX_29A5EC27BCF5E72D ON produit (categorie_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE produit_client');
        $this->addSql('ALTER TABLE avis DROP FOREIGN KEY FK_8F91ABF0F347EFB');
        $this->addSql('DROP INDEX IDX_8F91ABF0F347EFB ON avis');
        $this->addSql('ALTER TABLE avis DROP produit_id');
        $this->addSql('ALTER TABLE ligne DROP FOREIGN KEY FK_57F0DB83F347EFB');
        $this->addSql('DROP INDEX IDX_57F0DB83F347EFB ON ligne');
        $this->addSql('ALTER TABLE ligne DROP produit_id');
        $this->addSql('ALTER TABLE photo DROP FOREIGN KEY FK_14B78418F347EFB');
        $this->addSql('DROP INDEX IDX_14B78418F347EFB ON photo');
        $this->addSql('ALTER TABLE photo DROP produit_id');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC27139DF194');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC27BCF5E72D');
        $this->addSql('DROP INDEX IDX_29A5EC27139DF194 ON produit');
        $this->addSql('DROP INDEX IDX_29A5EC27BCF5E72D ON produit');
        $this->addSql('ALTER TABLE produit DROP promotion_id, DROP categorie_id');
    }
}
