<?php

namespace App\Presenters;

use App\Components\SetUserForm;
use App\Model\Security\User;
use App\Model\Security\UserFacade;

/**
 * Class UsersPresenter
 */
class UsersPresenter extends SecuredPresenter
{

	/** @var UserFacade @autowire */
	public $userFacade;

	/** @var User */
	private $selectedUser;

	/** @var User[] */
	private $users;

	public function startup()
	{
		parent::startup();

		$this->submenu->addSection('Uživatelé');
		$this->submenu->addItem("Users:default", 'Seznam uživatelů');
		$this->submenu->addItem("Users:addUser", 'Přidat uživatele');
	}

	public function actionDefault()
	{
		$this->users = $this->userFacade->getUsersList();
	}

	public function renderDefault()
	{
		$this->template->users = $this->users;
	}


	/**
	 * @param \App\Components\SetUserForm $factory
	 *
	 * @return \Nette\Application\UI\Form
	 */
	public function createComponentAddUserForm(SetUserForm $factory)
	{
		$form = $factory->create();
		$form->onSuccess[] = function () {
			$this->flashMessage('Uživatel byl přidán', "success");
			$this->redirect(':Users:default');
		};

		return $form;
	}


	/**
	 * @param int $id
	 */
	public function actionEditUser($id)
	{
		$this->selectedUser = $this->userFacade->findUserById($id);
		if (!$this->selectedUser) {
			$this->setView("notFound");
		}
	}


	/**
	 * @param \App\Components\SetUserForm $factory
	 *
	 * @return \Nette\Application\UI\Form
	 */
	public function createComponentEditUserForm(SetUserForm $factory)
	{
		$factory->setUser($this->selectedUser);
		$form = $factory->create();
		$form->onSuccess[] = function () {
			$this->flashMessage('Uživatel byl upraven', "success");
			$this->redirect(':Users:default');
		};

		return $form;
	}


	/**
	 * @param int $id
	 */
	public function handleDeleteUser($id)
	{
		$user = $this->userFacade->findUserById($id);
		$this->userFacade->deleteUser($user);
		$this->flashMessage('Uživatel byl smazán', "success");
		$this->redirect(":Users:default");
	}

}
