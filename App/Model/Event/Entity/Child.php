<?php

namespace App\Model\Event\Entity;

use App\Model\Security\User;
use Doctrine\ORM\Mapping as ORM;
use Kdyby\Doctrine\Entities\BaseEntity;

/**
 * @ORM\Entity()
 * @property $id
 * @property Event $event
 * @property User $teacher
 * @property $name
 * @property $songAuthor
 * @property $songName
 */
class Child extends BaseEntity
{

	/**
	 * @ORM\Column(name="id", type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/** @ORM\ManyToOne(targetEntity="Event", inversedBy="children") */
	protected $event;

	/**
	 * @ORM\ManyToOne(targetEntity="App\Model\Security\User")
	 * @var User
	 */
	protected $teacher;

	/** @ORM\Column(type="string") */
	protected $name;

	/** @ORM\Column(type="string") */
	protected $songAuthor;

	/** @ORM\Column(type="string") */
	protected $songName;



	public function __construct(Event $event, User $teacher)
	{
		$this->event = $event;
		$this->teacher = $teacher;
	}

}