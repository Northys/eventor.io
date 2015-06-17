<?php

namespace App\Presenters;

use App;
use App\Components\SetUserForm;

class UsersPresenter extends SecuredPresenter
{

	/** @var App\Model\Security\UserFacade @autowire */
	public $userFacade;

	/** @var App\Model\Security\User */
	private $selectedUser;



	public function startup()
	{
		parent::startup();

		$this->submenu->addSection('Uživatelé');
		$this->submenu->addItem("Users:default", 'Seznam uživatelů');
		$this->submenu->addItem("Users:addUser", 'Přidat uživatele');
	}



	/*
	 *
	 * default
	 *
	 */


	public function renderDefault()
	{
		$this->template->users = $this->userFacade->getUsersList();
	}



	/*
	 *
	 * addUser
	 *
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



	/*
	 *
	 * editUser
	 *
	 */


	public function actionEditUser($id)
	{
		$this->selectedUser = $this->userFacade->findUserById($id);
		if (!$this->selectedUser) {
			$this->setView("notFound");
		}
	}



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



	/*
	 *
	 * handlers
	 *
	 */


	public function handleDeleteUser($id)
	{
		$user = $this->userFacade->findUserById($id);
		$this->userFacade->deleteUser($user);
		$this->flashMessage('Uživatel byl smazán', "success");
		$this->redirect(":Users:default");
	}

}
