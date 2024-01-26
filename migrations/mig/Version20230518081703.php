<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230518081703 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX uniq_8d93d649e7927c74');
        $this->addSql('ALTER TABLE "user" ADD facebook VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE "user" ADD instagram VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE "user" ALTER username SET NOT NULL');
        $this->addSql('ALTER TABLE "user" ALTER email DROP NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649F85E0677 ON "user" (username)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP INDEX UNIQ_8D93D649F85E0677');
        $this->addSql('ALTER TABLE "user" DROP facebook');
        $this->addSql('ALTER TABLE "user" DROP instagram');
        $this->addSql('ALTER TABLE "user" ALTER username DROP NOT NULL');
        $this->addSql('ALTER TABLE "user" ALTER email SET NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX uniq_8d93d649e7927c74 ON "user" (email)');
    }
}
