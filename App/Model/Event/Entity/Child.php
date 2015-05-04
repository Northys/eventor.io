<?php

namespace App\Model\Event\Entity;

use Doctrine\ORM\Mapping as ORM;
use Kdyby\Doctrine\Entities\BaseEntity;

/**
 * @ORM\Entity()
 * @property $id
 * @property Event $event
 * @property $name
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

	/** @ORM\Column(type="string") */
	protected $name;



	public function __construct(Event $event)
	{
		$this->event = $event;
	}

}