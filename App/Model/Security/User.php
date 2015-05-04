<?php

namespace App\Model\Security;

use Doctrine\ORM\Mapping as ORM;
use Kdyby\Doctrine\Entities\BaseEntity;
use Nette\Security\IIdentity;

/**
 * @ORM\Entity()
 * @property $id
 * @property $name
 * @property $email
 * @property $password
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