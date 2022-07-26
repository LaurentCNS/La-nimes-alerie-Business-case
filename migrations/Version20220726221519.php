<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220726221519 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE avis ADD client_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE avis ADD CONSTRAINT FK_8F91ABF019EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('CREATE INDEX IDX_8F91ABF019EB6921 ON avis (client_id)');
        $this->addSql('ALTER TABLE client ADD genre_id INT DEFAULT NULL, ADD adresse_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE client ADD CONSTRAINT FK_C74404554296D31F FOREIGN KEY (genre_id) REFERENCES genre (id)');
        $this->addSql('ALTER TABLE client ADD CONSTRAINT FK_C74404554DE7DC5C FOREIGN KEY (adresse_id) REFERENCES adresse (id)');
        $this->addSql('CREATE INDEX IDX_C74404554296D31F ON client (genre_id)');
        $this->addSql('CREATE INDEX IDX_C74404554DE7DC5C ON client (adresse_id)');
        $this->addSql('ALTER TABLE panier ADD client_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE panier ADD CONSTRAINT FK_24CC0DF219EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('CREATE INDEX IDX_24CC0DF219EB6921 ON panier (client_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE avis DROP FOREIGN KEY FK_8F91ABF019EB6921');
        $this->addSql('DROP INDEX IDX_8F91ABF019EB6921 ON avis');
        $this->addSql('ALTER TABLE avis DROP client_id');
        $this->addSql('ALTER TABLE client DROP FOREIGN KEY FK_C74404554296D31F');
        $this->addSql('ALTER TABLE client DROP FOREIGN KEY FK_C74404554DE7DC5C');
        $this->addSql('DROP INDEX IDX_C74404554296D31F ON client');
        $this->addSql('DROP INDEX IDX_C74404554DE7DC5C ON client');
        $this->addSql('ALTER TABLE client DROP genre_id, DROP adresse_id');
        $this->addSql('ALTER TABLE panier DROP FOREIGN KEY FK_24CC0DF219EB6921');
        $this->addSql('DROP INDEX IDX_24CC0DF219EB6921 ON panier');
        $this->addSql('ALTER TABLE panier DROP client_id');
    }
}
