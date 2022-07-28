<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220728090302 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE commande');
        $this->addSql('ALTER TABLE panier ADD adresse_id INT DEFAULT NULL, ADD moyen_paiement_id INT DEFAULT NULL, ADD date_paiement DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE panier ADD CONSTRAINT FK_24CC0DF24DE7DC5C FOREIGN KEY (adresse_id) REFERENCES adresse (id)');
        $this->addSql('ALTER TABLE panier ADD CONSTRAINT FK_24CC0DF29C7E259C FOREIGN KEY (moyen_paiement_id) REFERENCES moyen_paiement (id)');
        $this->addSql('CREATE INDEX IDX_24CC0DF24DE7DC5C ON panier (adresse_id)');
        $this->addSql('CREATE INDEX IDX_24CC0DF29C7E259C ON panier (moyen_paiement_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE commande (id INT AUTO_INCREMENT NOT NULL, moyen_paiement_id INT DEFAULT NULL, adresse_id INT DEFAULT NULL, panier_id INT DEFAULT NULL, date_conversion DATETIME NOT NULL, date_facturation DATETIME NOT NULL, etat INT NOT NULL, INDEX IDX_6EEAA67D9C7E259C (moyen_paiement_id), INDEX IDX_6EEAA67D4DE7DC5C (adresse_id), UNIQUE INDEX UNIQ_6EEAA67DF77D927C (panier_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67D4DE7DC5C FOREIGN KEY (adresse_id) REFERENCES adresse (id)');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67DF77D927C FOREIGN KEY (panier_id) REFERENCES panier (id)');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67D9C7E259C FOREIGN KEY (moyen_paiement_id) REFERENCES moyen_paiement (id)');
        $this->addSql('ALTER TABLE panier DROP FOREIGN KEY FK_24CC0DF24DE7DC5C');
        $this->addSql('ALTER TABLE panier DROP FOREIGN KEY FK_24CC0DF29C7E259C');
        $this->addSql('DROP INDEX IDX_24CC0DF24DE7DC5C ON panier');
        $this->addSql('DROP INDEX IDX_24CC0DF29C7E259C ON panier');
        $this->addSql('ALTER TABLE panier DROP adresse_id, DROP moyen_paiement_id, DROP date_paiement');
    }
}
