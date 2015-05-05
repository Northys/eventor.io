<?php

namespace App\Model\Event\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Kdyby\Doctrine\Entities\BaseEntity;

/**
 * @ORM\Entity()
 * @property $id
 * @property Event $event
 * @property $songAuthor
 * @property $songName
 * @property Child[]|ArrayCollection $children
 * @property $note
 * @property $priority
 */
class Performance extends BaseEntity
{

	/**
	 * @ORM\Column(name="id", type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
	 * @ORM\ManyToOne(targetEntity="Event", inversedBy="performances")
	 * @var Event
	 */
	protected $event;

	/** @ORM\Column(type="string") */
	protected $songAuthor;

	/** @ORM\Column(type="string") */
	protected $songName;

	/**
	 * @ORM\OneToMany(targetEntity="Child", mappedBy="performance")
	 * @var Child[]|ArrayCollection
	 */
	protected $children;

	/** @ORM\Column(type="text") */
	protected $note;

	/** @ORM\Column(type="integer") */
	protected $priority;



	public function __construct(Event $event)
	{
		$this->event = $event;
		$this->children = new ArrayCollection();
	}

}