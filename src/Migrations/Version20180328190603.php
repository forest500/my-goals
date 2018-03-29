<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180328190603 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE refresh_tokens (id INT AUTO_INCREMENT NOT NULL, refresh_token VARCHAR(128) NOT NULL, username VARCHAR(255) NOT NULL, valid DATETIME NOT NULL, UNIQUE INDEX UNIQ_9BACE7E1C74F2195 (refresh_token), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE entity_user ADD activationCode LONGTEXT NOT NULL');
        $this->addSql('ALTER TABLE stage ADD name VARCHAR(100) NOT NULL, CHANGE award award VARCHAR(255) DEFAULT NULL, CHANGE end_date end_date DATE DEFAULT NULL');
        $this->addSql('ALTER TABLE goal DROP FOREIGN KEY FK_FCDCEB2E73EA2E9E');
        $this->addSql('DROP INDEX IDX_FCDCEB2E73EA2E9E ON goal');
        $this->addSql('ALTER TABLE goal CHANGE $userid userId INT DEFAULT NULL');
        $this->addSql('ALTER TABLE goal ADD CONSTRAINT FK_FCDCEB2E64B64DCC FOREIGN KEY (userId) REFERENCES entity_user (id)');
        $this->addSql('CREATE INDEX IDX_FCDCEB2E64B64DCC ON goal (userId)');
        $this->addSql('ALTER TABLE category DROP FOREIGN KEY FK_64C19C173EA2E9E');
        $this->addSql('DROP INDEX IDX_64C19C173EA2E9E ON category');
        $this->addSql('ALTER TABLE category CHANGE description description VARCHAR(255) DEFAULT NULL, CHANGE $userid userId INT DEFAULT NULL');
        $this->addSql('ALTER TABLE category ADD CONSTRAINT FK_64C19C164B64DCC FOREIGN KEY (userId) REFERENCES entity_user (id)');
        $this->addSql('CREATE INDEX IDX_64C19C164B64DCC ON category (userId)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE refresh_tokens');
        $this->addSql('ALTER TABLE category DROP FOREIGN KEY FK_64C19C164B64DCC');
        $this->addSql('DROP INDEX IDX_64C19C164B64DCC ON category');
        $this->addSql('ALTER TABLE category CHANGE description description VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, CHANGE userid $userId INT DEFAULT NULL');
        $this->addSql('ALTER TABLE category ADD CONSTRAINT FK_64C19C173EA2E9E FOREIGN KEY ($userId) REFERENCES entity_user (id)');
        $this->addSql('CREATE INDEX IDX_64C19C173EA2E9E ON category ($userId)');
        $this->addSql('ALTER TABLE entity_user DROP activationCode');
        $this->addSql('ALTER TABLE goal DROP FOREIGN KEY FK_FCDCEB2E64B64DCC');
        $this->addSql('DROP INDEX IDX_FCDCEB2E64B64DCC ON goal');
        $this->addSql('ALTER TABLE goal CHANGE userid $userId INT DEFAULT NULL');
        $this->addSql('ALTER TABLE goal ADD CONSTRAINT FK_FCDCEB2E73EA2E9E FOREIGN KEY ($userId) REFERENCES entity_user (id)');
        $this->addSql('CREATE INDEX IDX_FCDCEB2E73EA2E9E ON goal ($userId)');
        $this->addSql('ALTER TABLE stage DROP name, CHANGE award award VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, CHANGE end_date end_date DATE NOT NULL');
    }
}
