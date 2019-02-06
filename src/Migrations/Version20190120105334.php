<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190120105334 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE element (id INT AUTO_INCREMENT NOT NULL, creator_id INT NOT NULL, name VARCHAR(255) NOT NULL, mechanics LONGTEXT NOT NULL, origin LONGTEXT NOT NULL, INDEX IDX_41405E3961220EA6 (creator_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hero (id INT AUTO_INCREMENT NOT NULL, creator_id INT NOT NULL, name VARCHAR(255) NOT NULL, first_element VARCHAR(255) NOT NULL, second_element VARCHAR(255) NOT NULL, title VARCHAR(255) NOT NULL, appearance LONGTEXT NOT NULL, personality LONGTEXT NOT NULL, INDEX IDX_51CE6E8661220EA6 (creator_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE spell (id INT AUTO_INCREMENT NOT NULL, creator_id INT NOT NULL, name VARCHAR(255) NOT NULL, element VARCHAR(255) NOT NULL, rarity VARCHAR(255) NOT NULL, damage INT DEFAULT NULL, count SMALLINT DEFAULT NULL, speed VARCHAR(255) DEFAULT NULL, shield INT DEFAULT NULL, hit_type VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, resistance INT DEFAULT NULL, heal INT DEFAULT NULL, INDEX IDX_D03FCD8D61220EA6 (creator_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE test (id INT AUTO_INCREMENT NOT NULL, many_id INT NOT NULL, INDEX IDX_D87F7E0C1D9014BC (many_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE test2 (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(255) NOT NULL, password VARCHAR(4096) NOT NULL, email VARCHAR(255) NOT NULL, nickname VARCHAR(255) NOT NULL, gender VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE element ADD CONSTRAINT FK_41405E3961220EA6 FOREIGN KEY (creator_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE hero ADD CONSTRAINT FK_51CE6E8661220EA6 FOREIGN KEY (creator_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE spell ADD CONSTRAINT FK_D03FCD8D61220EA6 FOREIGN KEY (creator_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE test ADD CONSTRAINT FK_D87F7E0C1D9014BC FOREIGN KEY (many_id) REFERENCES test2 (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE test DROP FOREIGN KEY FK_D87F7E0C1D9014BC');
        $this->addSql('ALTER TABLE element DROP FOREIGN KEY FK_41405E3961220EA6');
        $this->addSql('ALTER TABLE hero DROP FOREIGN KEY FK_51CE6E8661220EA6');
        $this->addSql('ALTER TABLE spell DROP FOREIGN KEY FK_D03FCD8D61220EA6');
        $this->addSql('DROP TABLE element');
        $this->addSql('DROP TABLE hero');
        $this->addSql('DROP TABLE spell');
        $this->addSql('DROP TABLE test');
        $this->addSql('DROP TABLE test2');
        $this->addSql('DROP TABLE user');
    }
}
