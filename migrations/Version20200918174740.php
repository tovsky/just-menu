<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200918174740 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE restaurant_file');
        $this->addSql('ALTER TABLE file ADD restaurant_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', ADD active TINYINT(1) NOT NULL COMMENT \'Файл активный/удаленный\'');
        $this->addSql('ALTER TABLE file ADD CONSTRAINT FK_8C9F3610B1E7706E FOREIGN KEY (restaurant_id) REFERENCES restaurant (id)');
        $this->addSql('CREATE INDEX IDX_8C9F3610B1E7706E ON file (restaurant_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE restaurant_file (restaurant_id CHAR(36) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:uuid)\', file_id CHAR(36) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:uuid)\', INDEX IDX_4E89A67493CB796C (file_id), INDEX IDX_4E89A674B1E7706E (restaurant_id), PRIMARY KEY(restaurant_id, file_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE restaurant_file ADD CONSTRAINT FK_939FD76E93CB796C FOREIGN KEY (file_id) REFERENCES file (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE restaurant_file ADD CONSTRAINT FK_939FD76EBD91643B FOREIGN KEY (restaurant_id) REFERENCES restaurant (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE file DROP FOREIGN KEY FK_8C9F3610B1E7706E');
        $this->addSql('DROP INDEX IDX_8C9F3610B1E7706E ON file');
        $this->addSql('ALTER TABLE file DROP restaurant_id, DROP active');
    }
}
