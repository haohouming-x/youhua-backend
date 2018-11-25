<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181125130701 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE order_bill DROP FOREIGN KEY FK_37D023F437FDBD6D');
        $this->addSql('DROP INDEX IDX_37D023F437FDBD6D ON order_bill');
        $this->addSql('ALTER TABLE order_bill DROP consumer_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE order_bill ADD consumer_id INT NOT NULL');
        $this->addSql('ALTER TABLE order_bill ADD CONSTRAINT FK_37D023F437FDBD6D FOREIGN KEY (consumer_id) REFERENCES consumer (id)');
        $this->addSql('CREATE INDEX IDX_37D023F437FDBD6D ON order_bill (consumer_id)');
    }
}
