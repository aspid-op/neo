<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240209020908 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('
            CREATE TABLE IF NOT EXISTS neos (
                id INT AUTO_INCREMENT NOT NULL,
                date DATE NOT NULL,
                neo_reference_id INT NOT NULL,
                name VARCHAR(255) NOT NULL,
                speed DOUBLE PRECISION NOT NULL,
                is_hazardous TINYINT(1) NOT NULL,
                UNIQUE INDEX UNIQ_9FC801091DE20A4C (neo_reference_id),
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4
        ');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE IF EXISTS neos');
    }
}
