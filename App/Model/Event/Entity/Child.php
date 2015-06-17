<?php

namespace App\Model\Event\Entity;

use App\Model\Security\User;
use Doctrine\ORM\Mapping as ORM;
use Kdyby\Doctrine\Entities\BaseEntity;

/**
 * @ORM\Entity()
 * @property $id
 * @property User $teacher
 * @property $name
 * @property $songAuthor
 * @property $songName
 * @property $instrument
 * @property $class
 */
class Child extends BaseEntity
{

	/**
	 * @ORM\Column(name="id", type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
	 * @ORM\ManyToOne(targetEntity="App\Model\Security\User")
	 * @var User
	 */
	protected $teacher;

	/**
	 * @ORM\ManyToOne(targetEntity="Performance", inversedBy="children")
	 * @var Performance
	 */
	protected $performance;

	/** @ORM\Column(type="string") */
	protected $name;

	/** @ORM\Column(type="string") */
	protected $instrument;

	/** @ORM\Column(type="string", nullable=true) */
	protected $class;



	public function __construct(Performance $performance, User $teacher = null)
	{
		$this->performance = $performance;
		$this->teacher = $teacher;
	}

}
