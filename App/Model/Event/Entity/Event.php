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
 * @property Child[]|ArrayCollection $children
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

	/**
	 * @ORM\ManyToOne(targetEntity="App\Model\Security\User", inversedBy="events")
	 * @var User
	 */
	protected $user;

	/**
	 * @ORM\OneToMany(targetEntity="Child", mappedBy="event")
	 * @var Child[]|ArrayCollection
	 */
	protected $children;



	public function __construct(User $user)
	{
		$this->user = $user;
		$this->children = new ArrayCollection();
	}



	/**
	 * @return Child[]|ArrayCollection
	 */
	public function getChildren()
	{
		return $this->children;
	}

}