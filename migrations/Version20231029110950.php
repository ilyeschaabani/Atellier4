<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231029110950 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE book CHANGE ref ref INT NOT NULL');
        $this->addSql('ALTER TABLE book_reader CHANGE book_id book_id INT NOT NULL');
        $this->addSql('ALTER TABLE book_reader ADD CONSTRAINT FK_E5E882B116A2B381 FOREIGN KEY (book_id) REFERENCES book (ref)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE book CHANGE ref ref INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE book_reader DROP FOREIGN KEY FK_E5E882B116A2B381');
        $this->addSql('ALTER TABLE book_reader CHANGE book_id book_id VARCHAR(255) NOT NULL');
    }
}
