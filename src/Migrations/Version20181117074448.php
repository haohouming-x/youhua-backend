<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181117074448 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE consumer DROP type');
        $this->addSql('ALTER TABLE member ADD market_id INT NOT NULL');
        $this->addSql('ALTER TABLE member ADD CONSTRAINT FK_70E4FA78622F3F37 FOREIGN KEY (market_id) REFERENCES marketing (id)');
        $this->addSql('CREATE INDEX IDX_70E4FA78622F3F37 ON member (market_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE consumer ADD type ENUM(\'YK\', \'HY\') NOT NULL COLLATE utf8mb4_unicode_ci COMMENT \'(DC2Type:ConsumerType)\'');
        $this->addSql('ALTER TABLE member DROP FOREIGN KEY FK_70E4FA78622F3F37');
        $this->addSql('DROP INDEX IDX_70E4FA78622F3F37 ON member');
        $this->addSql('ALTER TABLE member DROP market_id');
    }
}
