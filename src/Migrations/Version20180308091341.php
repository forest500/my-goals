<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180308091341 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE category ADD $userId INT DEFAULT NULL');
        $this->addSql('ALTER TABLE category ADD CONSTRAINT FK_64C19C173EA2E9E FOREIGN KEY ($userId) REFERENCES entity_user (id)');
        $this->addSql('CREATE INDEX IDX_64C19C173EA2E9E ON category ($userId)');
        $this->addSql('ALTER TABLE goal ADD $userId INT DEFAULT NULL');
        $this->addSql('ALTER TABLE goal ADD CONSTRAINT FK_FCDCEB2E73EA2E9E FOREIGN KEY ($userId) REFERENCES entity_user (id)');
        $this->addSql('CREATE INDEX IDX_FCDCEB2E73EA2E9E ON goal ($userId)');
        $this->addSql('ALTER TABLE stage ADD userId INT DEFAULT NULL');
        $this->addSql('ALTER TABLE stage ADD CONSTRAINT FK_C27C936964B64DCC FOREIGN KEY (userId) REFERENCES entity_user (id)');
        $this->addSql('CREATE INDEX IDX_C27C936964B64DCC ON stage (userId)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE category DROP FOREIGN KEY FK_64C19C173EA2E9E');
        $this->addSql('DROP INDEX IDX_64C19C173EA2E9E ON category');
        $this->addSql('ALTER TABLE category DROP $userId');
        $this->addSql('ALTER TABLE goal DROP FOREIGN KEY FK_FCDCEB2E73EA2E9E');
        $this->addSql('DROP INDEX IDX_FCDCEB2E73EA2E9E ON goal');
        $this->addSql('ALTER TABLE goal DROP $userId');
        $this->addSql('ALTER TABLE stage DROP FOREIGN KEY FK_C27C936964B64DCC');
        $this->addSql('DROP INDEX IDX_C27C936964B64DCC ON stage');
        $this->addSql('ALTER TABLE stage DROP userId');
    }
}
