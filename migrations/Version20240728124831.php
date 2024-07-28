<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240728124831 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Fix invalid type for movie_id';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('
            ALTER TABLE redirects
                DROP movie_id,
                ADD movie_id VARCHAR(32) NOT NULL
        ');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('
            ALTER TABLE redirects
                DROP movie_id,
                ADD movie_id INT NOT NULL
        ');
    }
}
