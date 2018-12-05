<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181129154904 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE adresse');
        $this->addSql('DROP TABLE commande');
        $this->addSql('ALTER TABLE categorie DROP image_categorie');
        $this->addSql('DROP INDEX id_produit ON prix');
        $this->addSql('DROP INDEX id_user ON prix');
        $this->addSql('ALTER TABLE produit CHANGE photo photo LONGBLOB NOT NULL');
        $this->addSql('DROP INDEX user_Id ON utilisateur');
        $this->addSql('ALTER TABLE utilisateur CHANGE id id INT AUTO_INCREMENT NOT NULL, CHANGE etablissement_autre etablissement_autre VARCHAR(150) NOT NULL, CHANGE status_inscription status_inscription TINYINT(1) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE adresse (id INT AUTO_INCREMENT NOT NULL, id_utilisateur INT NOT NULL, ad_livraison VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, ad_facturation VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commande (id_utilisateur INT NOT NULL, id_produit INT NOT NULL, id INT NOT NULL, quantite VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, prix DOUBLE PRECISION NOT NULL, date_commande DATE NOT NULL, INDEX id_produit (id_produit), INDEX id_utilisateur (id_utilisateur)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT commande_ibfk_1 FOREIGN KEY (id_produit) REFERENCES produit (id)');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT commande_ibfk_2 FOREIGN KEY (id_utilisateur) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE categorie ADD image_categorie BLOB DEFAULT NULL');
        $this->addSql('CREATE INDEX id_produit ON prix (id_produit)');
        $this->addSql('CREATE INDEX id_user ON prix (id_utilisateur)');
        $this->addSql('ALTER TABLE produit CHANGE photo photo LONGBLOB DEFAULT NULL');
        $this->addSql('ALTER TABLE utilisateur CHANGE id id INT NOT NULL, CHANGE status_inscription status_inscription TINYINT(1) DEFAULT \'0\', CHANGE etablissement_autre etablissement_autre VARCHAR(150) DEFAULT NULL COLLATE utf8_general_ci');
        $this->addSql('CREATE INDEX user_Id ON utilisateur (user_Id)');
    }
}
