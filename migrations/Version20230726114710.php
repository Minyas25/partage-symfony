<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230726114710 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE annonce (id INT AUTO_INCREMENT NOT NULL, id_person_id INT NOT NULL, title VARCHAR(255) NOT NULL, desccription VARCHAR(255) NOT NULL, date DATE NOT NULL, item VARCHAR(255) DEFAULT NULL, INDEX IDX_F65593E5A14E0760 (id_person_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE emprunt (id INT AUTO_INCREMENT NOT NULL, id_person_id INT NOT NULL, id_annonce_id INT NOT NULL, message LONGTEXT DEFAULT NULL, date DATE NOT NULL, satus VARCHAR(255) NOT NULL, INDEX IDX_364071D7A14E0760 (id_person_id), INDEX IDX_364071D72D8F2BF8 (id_annonce_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE person (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, adress VARCHAR(255) DEFAULT NULL, password VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE annonce ADD CONSTRAINT FK_F65593E5A14E0760 FOREIGN KEY (id_person_id) REFERENCES person (id)');
        $this->addSql('ALTER TABLE emprunt ADD CONSTRAINT FK_364071D7A14E0760 FOREIGN KEY (id_person_id) REFERENCES person (id)');
        $this->addSql('ALTER TABLE emprunt ADD CONSTRAINT FK_364071D72D8F2BF8 FOREIGN KEY (id_annonce_id) REFERENCES annonce (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE annonce DROP FOREIGN KEY FK_F65593E5A14E0760');
        $this->addSql('ALTER TABLE emprunt DROP FOREIGN KEY FK_364071D7A14E0760');
        $this->addSql('ALTER TABLE emprunt DROP FOREIGN KEY FK_364071D72D8F2BF8');
        $this->addSql('DROP TABLE annonce');
        $this->addSql('DROP TABLE emprunt');
        $this->addSql('DROP TABLE person');
    }
}
