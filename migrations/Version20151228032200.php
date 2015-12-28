<?php

namespace Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Class Version20151228032200
 *
 * Created user developer@example.com / qwertz
 */
class Version20151228032200 extends AbstractMigration
{

	/**
	 * @param \Doctrine\DBAL\Schema\Schema $schema
	 */
	public function up(Schema $schema)
	{
		$this->addSql('INSERT INTO `users` (`id`, `name`, `email`, `password`) VALUES (1,	\'Developer\',	\'developer@example.com\',	\'$2y$07$1bjnm62ht53fznmcm8yvbOGZOBuZzafKwxYeNnZQ/r5o5hGYWT7vi\');');
	}


	/**
	 * @param \Doctrine\DBAL\Schema\Schema $schema
	 */
	public function down(Schema $schema)
	{
	}
}
