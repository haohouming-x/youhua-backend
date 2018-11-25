<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181123021816 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE order_order_bill (order_id INT NOT NULL, order_bill_id INT NOT NULL, INDEX IDX_840ADEA18D9F6D38 (order_id), INDEX IDX_840ADEA1ECF6BBBE (order_bill_id), PRIMARY KEY(order_id, order_bill_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE order_order_bill ADD CONSTRAINT FK_840ADEA18D9F6D38 FOREIGN KEY (order_id) REFERENCES `order` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE order_order_bill ADD CONSTRAINT FK_840ADEA1ECF6BBBE FOREIGN KEY (order_bill_id) REFERENCES order_bill (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE `order` DROP logistics_number, DROP freight_price');
        $this->addSql('ALTER TABLE order_bill DROP FOREIGN KEY FK_37D023F4ABF168B3');
        $this->addSql('DROP INDEX IDX_37D023F4ABF168B3 ON order_bill');
        $this->addSql('ALTER TABLE order_bill ADD consumer_id INT NOT NULL, CHANGE quantity quantity INT DEFAULT 1 NOT NULL, CHANGE order_info_id goods_id INT NOT NULL');
        $this->addSql('ALTER TABLE order_bill ADD CONSTRAINT FK_37D023F4B7683595 FOREIGN KEY (goods_id) REFERENCES goods (id)');
        $this->addSql('ALTER TABLE order_bill ADD CONSTRAINT FK_37D023F437FDBD6D FOREIGN KEY (consumer_id) REFERENCES consumer (id)');
        $this->addSql('CREATE INDEX IDX_37D023F4B7683595 ON order_bill (goods_id)');
        $this->addSql('CREATE INDEX IDX_37D023F437FDBD6D ON order_bill (consumer_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE order_order_bill');
        $this->addSql('ALTER TABLE `order` ADD logistics_number VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, ADD freight_price DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE order_bill DROP FOREIGN KEY FK_37D023F4B7683595');
        $this->addSql('ALTER TABLE order_bill DROP FOREIGN KEY FK_37D023F437FDBD6D');
        $this->addSql('DROP INDEX IDX_37D023F4B7683595 ON order_bill');
        $this->addSql('DROP INDEX IDX_37D023F437FDBD6D ON order_bill');
        $this->addSql('ALTER TABLE order_bill ADD order_info_id INT NOT NULL, DROP goods_id, DROP consumer_id, CHANGE quantity quantity INT NOT NULL');
        $this->addSql('ALTER TABLE order_bill ADD CONSTRAINT FK_37D023F4ABF168B3 FOREIGN KEY (order_info_id) REFERENCES `order` (id)');
        $this->addSql('CREATE INDEX IDX_37D023F4ABF168B3 ON order_bill (order_info_id)');
    }
}
