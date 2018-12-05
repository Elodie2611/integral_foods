<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181121150330 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE adresse');
        $this->addSql('DROP TABLE categorie');
        $this->addSql('DROP TABLE commande');
        $this->addSql('DROP TABLE utilisateur');
        $this->addSql('DROP INDEX reference ON produit');
        $this->addSql('DROP INDEX EAN ON produit');
        $this->addSql('ALTER TABLE produit ADD photo LONGBLOB NOT NULL, CHANGE description description VARCHAR(255) NOT NULL, CHANGE idcategorie id_categorie INT NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE adresse (id INT AUTO_INCREMENT NOT NULL, idUtilisateur INT NOT NULL, ad_livraison TEXT NOT NULL COLLATE utf8_general_ci, ad_facturation TEXT NOT NULL COLLATE utf8_general_ci, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categorie (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL COLLATE utf8_general_ci, UNIQUE INDEX libelle (libelle), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commande (id INT AUTO_INCREMENT NOT NULL, idUtilisateur INT NOT NULL, idProduit INT NOT NULL, quantite INT NOT NULL, prix DOUBLE PRECISION NOT NULL, date_commande DATE NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE utilisateur (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(100) NOT NULL COLLATE utf8_general_ci, prenom VARCHAR(100) NOT NULL COLLATE utf8_general_ci, civilite VARCHAR(10) NOT NULL COLLATE utf8_general_ci, entreprise VARCHAR(255) NOT NULL COLLATE utf8_general_ci, type_etablissement VARCHAR(150) NOT NULL COLLATE utf8_general_ci, etablissement_autre VARCHAR(150) NOT NULL COLLATE utf8_general_ci, status_inscription TINYINT(1) NOT NULL, tel INT NOT NULL, email VARCHAR(255) NOT NULL COLLATE utf8_general_ci, siret VARCHAR(14) NOT NULL COLLATE utf8_general_ci, kbis VARCHAR(255) NOT NULL COLLATE utf8_general_ci, login VARCHAR(150) NOT NULL COLLATE utf8_general_ci, mot_de_passe VARCHAR(150) NOT NULL COLLATE utf8_general_ci, description TEXT NOT NULL COLLATE utf8_general_ci, date_inscription DATE NOT NULL, UNIQUE INDEX login (login), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE produit DROP photo, CHANGE description description TEXT NOT NULL COLLATE utf8_general_ci, CHANGE id_categorie idCategorie INT NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX reference ON produit (reference)');
        $this->addSql('CREATE UNIQUE INDEX EAN ON produit (EAN)');
    }
}
