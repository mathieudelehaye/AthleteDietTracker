<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180821153213 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('CREATE TABLE aliment_category (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, description VARCHAR(50) NOT NULL, g_pro DOUBLE PRECISION NOT NULL, g_hyd DOUBLE PRECISION NOT NULL, g_fat DOUBLE PRECISION NOT NULL, g_fib DOUBLE PRECISION NOT NULL, ene DOUBLE PRECISION NOT NULL)');
        $this->addSql('DROP TABLE aliment_type');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('CREATE TABLE aliment_type (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, description VARCHAR(50) NOT NULL COLLATE BINARY, g_pro DOUBLE PRECISION NOT NULL, g_hyd DOUBLE PRECISION NOT NULL, g_fat DOUBLE PRECISION NOT NULL, g_fib DOUBLE PRECISION NOT NULL, ene DOUBLE PRECISION NOT NULL)');
        $this->addSql('DROP TABLE aliment_category');
    }
}
