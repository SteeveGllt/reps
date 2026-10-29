<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20261029143721 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE exercise (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, type VARCHAR(20) NOT NULL, muscle_group VARCHAR(50) DEFAULT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE template_exercise (id INT AUTO_INCREMENT NOT NULL, position SMALLINT NOT NULL, target_sets SMALLINT NOT NULL, reps_min SMALLINT NOT NULL, reps_max SMALLINT NOT NULL, template_id INT NOT NULL, exercise_id INT NOT NULL, INDEX IDX_F18CE56F5DA0FB8 (template_id), INDEX IDX_F18CE56FE934951A (exercise_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, pseudo VARCHAR(50) NOT NULL, date_inscription DATETIME NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE workout (id INT AUTO_INCREMENT NOT NULL, performed_at DATETIME NOT NULL, duration INT DEFAULT NULL, notes LONGTEXT DEFAULT NULL, owner_id INT NOT NULL, INDEX IDX_649FFB727E3C61F9 (owner_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE workout_set (id INT AUTO_INCREMENT NOT NULL, set_number SMALLINT NOT NULL, reps SMALLINT DEFAULT NULL, weight DOUBLE PRECISION DEFAULT NULL, duration INT DEFAULT NULL, distance DOUBLE PRECISION DEFAULT NULL, rpe SMALLINT DEFAULT NULL, workout_id INT NOT NULL, exercise_id INT NOT NULL, INDEX IDX_6FDEFB94A6CCCFC9 (workout_id), INDEX IDX_6FDEFB94E934951A (exercise_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE workout_template (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, owner_id INT NOT NULL, INDEX IDX_4F11F2357E3C61F9 (owner_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE template_exercise ADD CONSTRAINT FK_F18CE56F5DA0FB8 FOREIGN KEY (template_id) REFERENCES workout_template (id)');
        $this->addSql('ALTER TABLE template_exercise ADD CONSTRAINT FK_F18CE56FE934951A FOREIGN KEY (exercise_id) REFERENCES exercise (id)');
        $this->addSql('ALTER TABLE workout ADD CONSTRAINT FK_649FFB727E3C61F9 FOREIGN KEY (owner_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE workout_set ADD CONSTRAINT FK_6FDEFB94A6CCCFC9 FOREIGN KEY (workout_id) REFERENCES workout (id)');
        $this->addSql('ALTER TABLE workout_set ADD CONSTRAINT FK_6FDEFB94E934951A FOREIGN KEY (exercise_id) REFERENCES exercise (id)');
        $this->addSql('ALTER TABLE workout_template ADD CONSTRAINT FK_4F11F2357E3C61F9 FOREIGN KEY (owner_id) REFERENCES `user` (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE template_exercise DROP FOREIGN KEY FK_F18CE56F5DA0FB8');
        $this->addSql('ALTER TABLE template_exercise DROP FOREIGN KEY FK_F18CE56FE934951A');
        $this->addSql('ALTER TABLE workout DROP FOREIGN KEY FK_649FFB727E3C61F9');
        $this->addSql('ALTER TABLE workout_set DROP FOREIGN KEY FK_6FDEFB94A6CCCFC9');
        $this->addSql('ALTER TABLE workout_set DROP FOREIGN KEY FK_6FDEFB94E934951A');
        $this->addSql('ALTER TABLE workout_template DROP FOREIGN KEY FK_4F11F2357E3C61F9');
        $this->addSql('DROP TABLE exercise');
        $this->addSql('DROP TABLE template_exercise');
        $this->addSql('DROP TABLE `user`');
        $this->addSql('DROP TABLE workout');
        $this->addSql('DROP TABLE workout_set');
        $this->addSql('DROP TABLE workout_template');
    }
}
