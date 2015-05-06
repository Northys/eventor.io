<?php

namespace App\Model\Security;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Kdyby\Doctrine\Entities\BaseEntity;
use Nette\Security\IIdentity;

/**
 * @ORM\Entity()
 * @property $id
 * @property $name
 * @property $email
 * @property $password
 * @property $events
 * @property $class
 */
class User extends BaseEntity implements IIdentity
{

	/**
	 * @ORM\Column(name="id", type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/** @ORM\Column(type="string", nullable=true) */
	protected $name;

	/** @ORM\Column(type="string", unique=true) */
	protected $email;

	/** @ORM\Column(type="string") */
	protected $password;

	/**
	 * @ORM\OneToMany(targetEntity="App\Model\Event\Entity\Event", mappedBy="user")
	 * @var \App\Model\Event\Entity\Event[]|ArrayCollection
	 */
	protected $events;

	/** @ORM\Column(type="string", nullable=true) */
	protected $class;



	public function __construct()
	{
		$this->events = new ArrayCollection();
	}



	/**
	 * @return \App\Model\Event\Entity\Event[]|ArrayCollection
	 */
	public function getEvents()
	{
		return $this->events;
	}



	/**
	 * @param string
	 */
	public function setPassword($password)
	{
		$this->password = Authenticator::hashPassword($password);
	}



	public function getId()
	{
		return $this->id;
	}



	public function getRoles()
	{
		return array("admin");
	}

}