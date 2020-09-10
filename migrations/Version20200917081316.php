<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200917081316 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE restaurant_file DROP FOREIGN KEY FK_939FD76E93CB796C');
        $this->addSql('ALTER TABLE file ADD mime_type VARCHAR(255) NOT NULL COMMENT \'Тип загруженного файла\', ADD type VARCHAR(255) NOT NULL COMMENT \'Тип загруженного файла (меню, лого, бэкграунд)\', ADD size INT NOT NULL COMMENT \'Размер загруженного файла\', DROP link, DROP is_active, CHANGE id id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', CHANGE phisical_file_name physical_file_name VARCHAR(512) NOT NULL COMMENT \'Имя файла в хранилище после загрузки\'');
        $this->addSql('ALTER TABLE file CHANGE id id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE restaurant_file CHANGE file_id file_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE restaurant_file ADD CONSTRAINT FK_939FD76E93CB796C FOREIGN KEY (file_id) REFERENCES file (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE restaurant_file DROP FOREIGN KEY FK_939FD76E93CB796C');
        $this->addSql('ALTER TABLE file ADD link VARCHAR(512) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'Url до файла\', ADD is_active TINYINT(1) NOT NULL COMMENT \'Доступность для просмотра/скачки\', DROP mime_type, DROP type, DROP size, CHANGE id id INT AUTO_INCREMENT NOT NULL, CHANGE physical_file_name phisical_file_name VARCHAR(512) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'Имя файла в хранилище после загрузки\'');
        $this->addSql('ALTER TABLE file CHANGE id id CHAR(36) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE restaurant_file CHANGE file_id file_id INT NOT NULL');
        $this->addSql('ALTER TABLE restaurant_file ADD CONSTRAINT FK_939FD76E93CB796C FOREIGN KEY (file_id) REFERENCES file (id) ON DELETE CASCADE');
    }
}
