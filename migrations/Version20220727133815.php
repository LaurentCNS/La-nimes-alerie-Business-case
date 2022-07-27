<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220727133815 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67DF6203804');
        $this->addSql('DROP TABLE statut');
        $this->addSql('DROP INDEX IDX_6EEAA67DF6203804 ON commande');
        $this->addSql('ALTER TABLE commande ADD etat INT NOT NULL, DROP statut_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE statut (id INT AUTO_INCREMENT NOT NULL, etat VARCHAR(30) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE commande ADD statut_id INT DEFAULT NULL, DROP etat');

        $this->addSql('CREATE INDEX IDX_6EEAA67DF6203804 ON commande (statut_id)');
    }
}
