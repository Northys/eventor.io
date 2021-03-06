<?php

namespace App\Components;

use Nette\Application\UI\Form;
use Nette\Object;
use Nette\Security\AuthenticationException;
use Nette\Security\User;
use Nextras\Forms\Rendering\Bs3FormRenderer;

class SignInForm extends Object
{

	/** @var User */
	private $user;


	/**
	 * SignInForm constructor.
	 *
	 * @param \Nette\Security\User $user
	 */
	public function __construct(User $user)
	{
		$this->user = $user;
	}


	/**
	 * @return \Nette\Application\UI\Form
	 */
	public function create()
	{
		$form = new Form();
		$form->addGroup("Přihlásit se");
		$form->addText('email', 'Email:', 50)
			->setType("email")
			->setRequired(Form::FILLED, 'Vyplňte prosím email');

		$form->addPassword('password', 'Heslo:', 50)
			->addRule(Form::FILLED, 'Vyplňte prosím heslo:');
		$form->addCheckbox('persistent', 'Pamatovat si mě na tomto počítači');
		$form->addSubmit('login', 'Přihlásit se');

		$form->onSuccess[] = $this->processForm;
		$form->setRenderer(new Bs3FormRenderer());

		return $form;
	}


	/**
	 * @param \Nette\Application\UI\Form $form
	 */
	public function processForm(Form $form)
	{
		try {
			if ($form->values->persistent) {
				$this->user->setExpiration('+30 days', FALSE);
			}
			$this->user->login($form->values->email, $form->values->password);
		} catch (AuthenticationException $e) {
			$form->addError('Nesprávné uživatelské údaje');
		}
	}

}
