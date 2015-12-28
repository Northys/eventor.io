<?php

namespace App\Model\Event\Entity;

use App\Model\Security\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Kdyby\Doctrine\Entities\BaseEntity;

/**
 * @ORM\Entity()
 * @property $id
 * @property $name
 * @property $date
 * @property $place
 * @property $note
 * @property Performance[]|ArrayCollection $performances
 */
class Event extends BaseEntity
{

	/**
	 * @ORM\Column(name="id", type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/** @ORM\Column(type="string") */
	protected $name;

	/** @ORM\Column(type="datetime") */
	protected $date;

	/** @ORM\Column(type="string") */
	protected $place;

	/** @ORM\Column(type="text") */
	protected $note;

	/**
	 * @ORM\ManyToOne(targetEntity="App\Model\Security\User", inversedBy="events")
	 * @var User
	 */
	protected $user;

	/**
	 * @ORM\OneToMany(targetEntity="Performance", mappedBy="event", orphanRemoval=true)
	 * @ORM\OrderBy({"position" = "ASC"})
	 * @var Performance[]|ArrayCollection
	 */
	protected $performances;


	/**
	 * Event constructor.
	 *
	 * @param \App\Model\Security\User $user
	 */
	public function __construct(User $user)
	{
		$this->user = $user;
		$this->performances = new ArrayCollection();
	}



	/**
	 * @return Performance[]|ArrayCollection
	 */
	public function getPerformances()
	{
		return $this->performances;
	}

}
