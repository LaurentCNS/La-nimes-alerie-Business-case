<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220726222557 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commande ADD moyen_paiement_id INT DEFAULT NULL, ADD statut_id INT DEFAULT NULL, ADD adresse_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67D9C7E259C FOREIGN KEY (moyen_paiement_id) REFERENCES moyen_paiement (id)');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67DF6203804 FOREIGN KEY (statut_id) REFERENCES statut (id)');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67D4DE7DC5C FOREIGN KEY (adresse_id) REFERENCES adresse (id)');
        $this->addSql('CREATE INDEX IDX_6EEAA67D9C7E259C ON commande (moyen_paiement_id)');
        $this->addSql('CREATE INDEX IDX_6EEAA67DF6203804 ON commande (statut_id)');
        $this->addSql('CREATE INDEX IDX_6EEAA67D4DE7DC5C ON commande (adresse_id)');
        $this->addSql('ALTER TABLE ligne ADD panier_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE ligne ADD CONSTRAINT FK_57F0DB83F77D927C FOREIGN KEY (panier_id) REFERENCES panier (id)');
        $this->addSql('CREATE INDEX IDX_57F0DB83F77D927C ON ligne (panier_id)');
        $this->addSql('ALTER TABLE panier ADD commande_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE panier ADD CONSTRAINT FK_24CC0DF282EA2E54 FOREIGN KEY (commande_id) REFERENCES commande (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_24CC0DF282EA2E54 ON panier (commande_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67D9C7E259C');
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67DF6203804');
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67D4DE7DC5C');
        $this->addSql('DROP INDEX IDX_6EEAA67D9C7E259C ON commande');
        $this->addSql('DROP INDEX IDX_6EEAA67DF6203804 ON commande');
        $this->addSql('DROP INDEX IDX_6EEAA67D4DE7DC5C ON commande');
        $this->addSql('ALTER TABLE commande DROP moyen_paiement_id, DROP statut_id, DROP adresse_id');
        $this->addSql('ALTER TABLE ligne DROP FOREIGN KEY FK_57F0DB83F77D927C');
        $this->addSql('DROP INDEX IDX_57F0DB83F77D927C ON ligne');
        $this->addSql('ALTER TABLE ligne DROP panier_id');
        $this->addSql('ALTER TABLE panier DROP FOREIGN KEY FK_24CC0DF282EA2E54');
        $this->addSql('DROP INDEX UNIQ_24CC0DF282EA2E54 ON panier');
        $this->addSql('ALTER TABLE panier DROP commande_id');
    }
}
