<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181121124003 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F5299398ECF6BBBE');
        $this->addSql('DROP INDEX IDX_F5299398ECF6BBBE ON `order`');
        $this->addSql('ALTER TABLE `order` DROP order_bill_id');
        $this->addSql('ALTER TABLE order_bill ADD order_info_id INT NOT NULL');
        $this->addSql('ALTER TABLE order_bill ADD CONSTRAINT FK_37D023F4ABF168B3 FOREIGN KEY (order_info_id) REFERENCES `order` (id)');
        $this->addSql('CREATE INDEX IDX_37D023F4ABF168B3 ON order_bill (order_info_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE `order` ADD order_bill_id INT NOT NULL');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F5299398ECF6BBBE FOREIGN KEY (order_bill_id) REFERENCES order_bill (id)');
        $this->addSql('CREATE INDEX IDX_F5299398ECF6BBBE ON `order` (order_bill_id)');
        $this->addSql('ALTER TABLE order_bill DROP FOREIGN KEY FK_37D023F4ABF168B3');
        $this->addSql('DROP INDEX IDX_37D023F4ABF168B3 ON order_bill');
        $this->addSql('ALTER TABLE order_bill DROP order_info_id');
    }
}
