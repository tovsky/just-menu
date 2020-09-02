<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200902065951 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE restaurant_user (restaurant_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', user_id INT NOT NULL, INDEX IDX_4F85462DB1E7706E (restaurant_id), INDEX IDX_4F85462DA76ED395 (user_id), PRIMARY KEY(restaurant_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE restaurant_user ADD CONSTRAINT FK_4F85462DB1E7706E FOREIGN KEY (restaurant_id) REFERENCES restaurant (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE restaurant_user ADD CONSTRAINT FK_4F85462DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE restaurant DROP FOREIGN KEY FK_3A3C5C82A76ED395');
        $this->addSql('DROP INDEX IDX_3A3C5C82A76ED395 ON restaurant');
        $this->addSql('ALTER TABLE restaurant ADD email VARCHAR(255) NOT NULL COMMENT \'Почта ресторана\', ADD web_site VARCHAR(255) DEFAULT NULL COMMENT \'Сайт ресторана\', ADD wifi_name VARCHAR(255) DEFAULT NULL COMMENT \'Наименование wi-fi\', ADD wifi_pass VARCHAR(255) DEFAULT NULL COMMENT \'Пароль от wi-fi\', DROP user_id, DROP legal_name, DROP inn, CHANGE name name VARCHAR(255) NOT NULL COMMENT \'Название ресторана\', CHANGE slug slug VARCHAR(255) NOT NULL COMMENT \'Слаг для ресторана\', CHANGE description description LONGTEXT DEFAULT NULL COMMENT \'Описание ресторана\', CHANGE address address VARCHAR(255) NOT NULL COMMENT \'Месторасположение ресторана\', CHANGE phone phone VARCHAR(255) DEFAULT NULL COMMENT \'Телефон ресторана\'');
        $this->addSql('ALTER TABLE restaurant_file RENAME INDEX idx_939fd76ebd91643b TO IDX_4E89A674B1E7706E');
        $this->addSql('ALTER TABLE restaurant_file RENAME INDEX idx_939fd76e93cb796c TO IDX_4E89A67493CB796C');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE restaurant_user');
        $this->addSql('ALTER TABLE restaurant ADD user_id INT DEFAULT NULL, ADD legal_name VARCHAR(512) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'Юридическое наименование организации\', ADD inn VARCHAR(14) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, DROP email, DROP web_site, DROP wifi_name, DROP wifi_pass, CHANGE name name VARCHAR(512) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'Название ресторана\', CHANGE slug slug VARCHAR(512) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'Слаг для ресторана\', CHANGE description description VARCHAR(2048) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'Описание ресторана\', CHANGE address address VARCHAR(512) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'Месторасположение ресторана\', CHANGE phone phone VARCHAR(14) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE restaurant ADD CONSTRAINT FK_3A3C5C82A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_3A3C5C82A76ED395 ON restaurant (user_id)');
        $this->addSql('ALTER TABLE restaurant_file RENAME INDEX idx_4e89a67493cb796c TO IDX_939FD76E93CB796C');
        $this->addSql('ALTER TABLE restaurant_file RENAME INDEX idx_4e89a674b1e7706e TO IDX_939FD76EBD91643B');
    }
}
