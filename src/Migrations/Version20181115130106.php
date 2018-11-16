<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181115130106 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE goods DROP FOREIGN KEY FK_563B92DECF6BBBE');
        $this->addSql('DROP INDEX IDX_563B92DECF6BBBE ON goods');
        $this->addSql('ALTER TABLE goods DROP order_bill_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE goods ADD order_bill_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE goods ADD CONSTRAINT FK_563B92DECF6BBBE FOREIGN KEY (order_bill_id) REFERENCES order_bill (id)');
        $this->addSql('CREATE INDEX IDX_563B92DECF6BBBE ON goods (order_bill_id)');
    }
}
