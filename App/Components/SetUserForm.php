<?php

namespace App\Components;

use App\Model\Security\User;
use App\Model\Security\UserFacade;
use Kdyby\BootstrapFormRenderer\BootstrapRenderer;
use Nette\Application\UI\Form;
use Nette\Object;

class SetUserForm extends Object
{

	/** @var \App\Model\Security\UserFacade */
	private $userFacade;

	/** @var User */
	private $user;



	public function __construct(UserFacade $userFacade)
	{
		$this->userFacade = $userFacade;
	}



	public function setUser(User $user)
	{
		$this->user = $user;
	}



	public function create()
	{
		$form = new Form();
		$form->addGroup(($this->user ? 'Upravit uživatele' : 'Přidat uživatele'));
		$form->addText("name", 'Jméno:')
			 ->setRequired('Vyplňte jméno');
		$form->addText("email", 'Email:')
			 ->setRequired('Vyplňte email')
			 ->addRule(function ($ctrl) {
				 if ($this->user and $this->user->email == $ctrl->getValue()) {
					 return TRUE;
				 }

				 return (bool) !$this->userFacade->findUserByEmail($ctrl->getValue());
			 }, 'Email je obsazen, zvolte prosím jiný');
		$password = $form->addPassword("password", 'Heslo:');
		$password2 = $form->addPassword("password2", 'Heslo znovu:');
		if (!$this->user) {
			$password->setRequired('Vyplňte heslo');
			$password2->addRule(Form::FILLED, 'Vyplňte heslo znovu pro kontrolu')
					  ->addRule(Form::EQUAL, 'Hesla se neshodují', $password);
		} else {
			$password2->addConditionOn($password, Form::FILLED)
					  ->setRequired('Vyplňte heslo znovu pro kontrolu')
					  ->addRule(Form::EQUAL, 'Hesla se neshodují', $password);
		}
		$form->addText("class", "Třída:");
		$form->addSubmit("send", ($this->user ? 'Upravit uživatele' : 'Přidat uživatele'));

		$form->setRenderer(new BootstrapRenderer());
		$form->onSuccess[] = $this->processForm;
		if ($this->user) {
			$form->setDefaults(array(
				"name" => $this->user->name,
				"email" => $this->user->email,
			));
		}

		return $form;
	}



	public function processForm(Form $form)
	{
		$values = $form->values;
		if ($this->user) {
			$user = $this->user;
			$user->name = $values->name;
			$user->email = $values->email;
			if ($values->password) {
				$user->setPassword($values->password);
			}
			$user->class = $values->class;
			$this->userFacade->save($user);
		} else {
			$this->user = $this->userFacade->createUser($values->name, $values->email, $values->password);
			$this->user->class = $values->class;
			$this->userFacade->save($this->user);
		}
	}

}