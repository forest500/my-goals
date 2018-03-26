<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180323091524 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE entity_user (id INT AUTO_INCREMENT NOT NULL, password VARCHAR(64) NOT NULL, email VARCHAR(64) NOT NULL, activationCode LONGTEXT NOT NULL, isActive TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_C55F6F62E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE stage (id INT AUTO_INCREMENT NOT NULL, goal_id INT DEFAULT NULL, number INT NOT NULL, name VARCHAR(100) NOT NULL, status TINYINT(1) NOT NULL, award VARCHAR(255) DEFAULT NULL, end_date DATE DEFAULT NULL, userId INT DEFAULT NULL, INDEX IDX_C27C9369667D1AFE (goal_id), INDEX IDX_C27C936964B64DCC (userId), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE goal (id INT AUTO_INCREMENT NOT NULL, category_id INT DEFAULT NULL, name VARCHAR(100) NOT NULL, status TINYINT(1) NOT NULL, $userId INT DEFAULT NULL, INDEX IDX_FCDCEB2E12469DE2 (category_id), INDEX IDX_FCDCEB2E73EA2E9E ($userId), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, description VARCHAR(255) DEFAULT NULL, $userId INT DEFAULT NULL, INDEX IDX_64C19C173EA2E9E ($userId), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE refresh_tokens (id INT AUTO_INCREMENT NOT NULL, refresh_token VARCHAR(128) NOT NULL, username VARCHAR(255) NOT NULL, valid DATETIME NOT NULL, UNIQUE INDEX UNIQ_9BACE7E1C74F2195 (refresh_token), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE stage ADD CONSTRAINT FK_C27C9369667D1AFE FOREIGN KEY (goal_id) REFERENCES goal (id)');
        $this->addSql('ALTER TABLE stage ADD CONSTRAINT FK_C27C936964B64DCC FOREIGN KEY (userId) REFERENCES entity_user (id)');
        $this->addSql('ALTER TABLE goal ADD CONSTRAINT FK_FCDCEB2E12469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE goal ADD CONSTRAINT FK_FCDCEB2E73EA2E9E FOREIGN KEY ($userId) REFERENCES entity_user (id)');
        $this->addSql('ALTER TABLE category ADD CONSTRAINT FK_64C19C173EA2E9E FOREIGN KEY ($userId) REFERENCES entity_user (id)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE stage DROP FOREIGN KEY FK_C27C936964B64DCC');
        $this->addSql('ALTER TABLE goal DROP FOREIGN KEY FK_FCDCEB2E73EA2E9E');
        $this->addSql('ALTER TABLE category DROP FOREIGN KEY FK_64C19C173EA2E9E');
        $this->addSql('ALTER TABLE stage DROP FOREIGN KEY FK_C27C9369667D1AFE');
        $this->addSql('ALTER TABLE goal DROP FOREIGN KEY FK_FCDCEB2E12469DE2');
        $this->addSql('DROP TABLE entity_user');
        $this->addSql('DROP TABLE stage');
        $this->addSql('DROP TABLE goal');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE refresh_tokens');
    }
}
