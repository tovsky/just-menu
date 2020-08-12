<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200812222125 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE restoraunt (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', user_id INT DEFAULT NULL, name VARCHAR(512) NOT NULL COMMENT \'Название ресторана\', slug VARCHAR(512) NOT NULL COMMENT \'Слаг для ресторана\', description VARCHAR(2048) DEFAULT NULL COMMENT \'Описание ресторана\', address VARCHAR(512) NOT NULL COMMENT \'Месторасположение ресторана\', phone VARCHAR(14) DEFAULT NULL, legal_name VARCHAR(512) DEFAULT NULL COMMENT \'Юридическое наименование организации\', inn VARCHAR(14) DEFAULT NULL, INDEX IDX_3A3C5C82A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'Сведения о точке общественного питания\' ');
        $this->addSql('CREATE TABLE restoraunt_file (restoraunt_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', file_id INT NOT NULL, INDEX IDX_939FD76EBD91643B (restoraunt_id), INDEX IDX_939FD76E93CB796C (file_id), PRIMARY KEY(restoraunt_id, file_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE restoraunt ADD CONSTRAINT FK_3A3C5C82A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE restoraunt_file ADD CONSTRAINT FK_939FD76EBD91643B FOREIGN KEY (restoraunt_id) REFERENCES restoraunt (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE restoraunt_file ADD CONSTRAINT FK_939FD76E93CB796C FOREIGN KEY (file_id) REFERENCES file (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE restoraunt_file DROP FOREIGN KEY FK_939FD76EBD91643B');
        $this->addSql('DROP TABLE restoraunt');
        $this->addSql('DROP TABLE restoraunt_file');
    }
}
