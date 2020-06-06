<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180821173115 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('CREATE TEMPORARY TABLE __temp__aliment AS SELECT id, meal_id, position, aliment_type_id, quantity FROM aliment');
        $this->addSql('DROP TABLE aliment');
        $this->addSql('CREATE TABLE aliment (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, meal_id INTEGER NOT NULL, position INTEGER NOT NULL, quantity INTEGER NOT NULL, aliment_category_id INTEGER NOT NULL)');
        $this->addSql('INSERT INTO aliment (id, meal_id, position, aliment_category_id, quantity) SELECT id, meal_id, position, aliment_type_id, quantity FROM __temp__aliment');
        $this->addSql('DROP TABLE __temp__aliment');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('CREATE TEMPORARY TABLE __temp__aliment AS SELECT id, meal_id, position, aliment_category_id, quantity FROM aliment');
        $this->addSql('DROP TABLE aliment');
        $this->addSql('CREATE TABLE aliment (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, meal_id INTEGER NOT NULL, position INTEGER NOT NULL, quantity INTEGER NOT NULL, aliment_type_id INTEGER NOT NULL)');
        $this->addSql('INSERT INTO aliment (id, meal_id, position, aliment_type_id, quantity) SELECT id, meal_id, position, aliment_category_id, quantity FROM __temp__aliment');
        $this->addSql('DROP TABLE __temp__aliment');
    }
}
