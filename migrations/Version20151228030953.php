<?php

namespace Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Class Version20151228030953
 *
 * Initial database state
 */
class Version20151228030953 extends AbstractMigration
{

	/**
	 * @param \Doctrine\DBAL\Schema\Schema $schema
	 */
	public function up(Schema $schema)
	{
		$this->addSql('CREATE TABLE `seoMeta` (id INT AUTO_INCREMENT NOT NULL, target_id INT DEFAULT NULL, seo_title VARCHAR(255) DEFAULT NULL, seo_keywords VARCHAR(255) DEFAULT NULL, seo_description LONGTEXT DEFAULT NULL, seo_robots VARCHAR(255) DEFAULT NULL, sitemap_change_freq VARCHAR(255) DEFAULT NULL, sitemap_priority VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_55330FAA158E0B66 (target_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;');
		$this->addSql('CREATE TABLE `seoRoute` (id INT AUTO_INCREMENT NOT NULL, target_id INT DEFAULT NULL, slug VARCHAR(255) NOT NULL, one_way TINYINT(1) NOT NULL, INDEX IDX_62F66FB7158E0B66 (target_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;');
		$this->addSql('CREATE TABLE `seoSettings` (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, value LONGTEXT NOT NULL, UNIQUE INDEX UNIQ_C14219A55E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;');
		$this->addSql('CREATE TABLE `seoTarget` (id INT AUTO_INCREMENT NOT NULL, meta_id INT DEFAULT NULL, target_presenter VARCHAR(255) NOT NULL, target_action VARCHAR(255) NOT NULL, target_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_3AD3F20439FCA6F9 (meta_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;');
		$this->addSql('CREATE TABLE child (id INT AUTO_INCREMENT NOT NULL, teacher_id INT DEFAULT NULL, performance_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, instrument VARCHAR(255) NOT NULL, class VARCHAR(255) DEFAULT NULL, INDEX IDX_22B3542941807E1D (teacher_id), INDEX IDX_22B35429B91ADEEE (performance_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;');
		$this->addSql('CREATE TABLE event (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, date DATETIME NOT NULL, place VARCHAR(255) NOT NULL, note LONGTEXT NOT NULL, INDEX IDX_3BAE0AA7A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;');
		$this->addSql('CREATE TABLE performance (id INT AUTO_INCREMENT NOT NULL, event_id INT DEFAULT NULL, song_author VARCHAR(255) NOT NULL, song_name VARCHAR(255) NOT NULL, note LONGTEXT NOT NULL, position INT DEFAULT NULL, INDEX IDX_82D7968171F7E88B (event_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;');
		$this->addSql('CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) DEFAULT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_1483A5E9E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;');
		$this->addSql('ALTER TABLE `seoMeta` ADD CONSTRAINT FK_55330FAA158E0B66 FOREIGN KEY (target_id) REFERENCES `seoTarget` (id);');
		$this->addSql('ALTER TABLE `seoRoute` ADD CONSTRAINT FK_62F66FB7158E0B66 FOREIGN KEY (target_id) REFERENCES `seoTarget` (id);');
		$this->addSql('ALTER TABLE `seoTarget` ADD CONSTRAINT FK_3AD3F20439FCA6F9 FOREIGN KEY (meta_id) REFERENCES `seoMeta` (id);');
		$this->addSql('ALTER TABLE child ADD CONSTRAINT FK_22B3542941807E1D FOREIGN KEY (teacher_id) REFERENCES users (id);');
		$this->addSql('ALTER TABLE child ADD CONSTRAINT FK_22B35429B91ADEEE FOREIGN KEY (performance_id) REFERENCES performance (id);');
		$this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA7A76ED395 FOREIGN KEY (user_id) REFERENCES users (id);');
		$this->addSql('ALTER TABLE performance ADD CONSTRAINT FK_82D7968171F7E88B FOREIGN KEY (event_id) REFERENCES event (id);');
	}


	/**
	 * @param \Doctrine\DBAL\Schema\Schema $schema
	 */
	public function down(Schema $schema)
	{
	}
}
