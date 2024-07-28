<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240728110654 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Initial';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('
            CREATE TABLE `redirects` (
                `id` CHAR(40) NOT NULL PRIMARY KEY,
                `movie_id` INT NOT NULL
            )
        ');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('
            DROP TABLE `redirects`
        ');
    }
}
