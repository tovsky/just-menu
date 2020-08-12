<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200812214227 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE file (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, link VARCHAR(512) NOT NULL COMMENT \'Url до файла\', phisical_file_name VARCHAR(512) NOT NULL COMMENT \'Имя файла в хранилище после загрузки\', name VARCHAR(512) NOT NULL COMMENT \'Имя загруженного файла\', is_active TINYINT(1) NOT NULL COMMENT \'Доступность для просмотра/скачки\', created_at DATETIME NOT NULL COMMENT \'Дата загрузки файла\', INDEX IDX_8C9F3610A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'Файлы загруженные пользователями\' ');
        $this->addSql('ALTER TABLE file ADD CONSTRAINT FK_8C9F3610A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE file');
    }
}
