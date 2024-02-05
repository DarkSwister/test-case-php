<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240122114135 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE account (id INT AUTO_INCREMENT NOT NULL, client_id INT DEFAULT NULL, balance INT NOT NULL, currency VARCHAR(6) NOT NULL, INDEX IDX_7D3656A419EB6921 (client_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE client (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE currency_rate (id INT AUTO_INCREMENT NOT NULL, base VARCHAR(255) NOT NULL, target VARCHAR(255) NOT NULL, rate DOUBLE PRECISION NOT NULL, date DATE NOT NULL COMMENT \'(DC2Type:date_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE transaction (id INT AUTO_INCREMENT NOT NULL, sender_id INT NOT NULL, receiver_id INT NOT NULL, currency VARCHAR(255) NOT NULL, amount INT NOT NULL, type VARCHAR(10) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_723705D1F624B39D (sender_id), INDEX IDX_723705D1CD53EDB6 (receiver_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE account ADD CONSTRAINT FK_7D3656A419EB6921 FOREIGN KEY (client_id) REFERENCES client (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D1F624B39D FOREIGN KEY (sender_id) REFERENCES account (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D1CD53EDB6 FOREIGN KEY (receiver_id) REFERENCES account (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE account DROP FOREIGN KEY FK_7D3656A419EB6921');
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D1F624B39D');
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D1CD53EDB6');
        $this->addSql('DROP TABLE account');
        $this->addSql('DROP TABLE client');
        $this->addSql('DROP TABLE currency_rate');
        $this->addSql('DROP TABLE transaction');
    }
}
