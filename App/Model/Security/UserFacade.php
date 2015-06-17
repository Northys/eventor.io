<?php

namespace App\Model\Security;

use Kdyby\Doctrine\EntityDao;
use Nette;

class UserFacade extends Nette\Object
{

	/** @var \Kdyby\Doctrine\EntityDao */
	private $userDao;

	/** @var array */
	public $onUserCreated = [];



	public function __construct(EntityDao $userDao)
	{
		$this->userDao = $userDao;
	}



	/**
	 * @param $name
	 * @param $email
	 * @param $password
	 * @return bool|User
	 */
	public function createUser($name, $email, $password)
	{
		$user = new User();
		$user->name = $name;
		$user->email = $email;
		$user->password = $password;

		if ($this->findUserByEmail($user->email)) {
			return FALSE;
		}
		$user = $this->userDao->save($user);

		$this->onUserCreated($user);

		return $user;
	}



	/**
	 * @return User[]
	 */
	public function getUsersList()
	{
		return $this->userDao->findAll();
	}



	/**
	 * @param $id
	 * @return null|User
	 */
	public function findUserById($id)
	{
		return $this->userDao->find($id);
	}



	/**
	 * @param $email
	 * @return null|User
	 */
	public function findUserByEmail($email)
	{
		return $this->userDao->findOneBy(["email" => $email]);
	}



	/**
	 * @param User $user
	 */
	public function deleteUser(User $user)
	{
		$this->userDao->delete($user);
	}



	public function save(User $user)
	{
		$this->userDao->save($user);
	}

}
