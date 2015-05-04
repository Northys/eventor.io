<?php

namespace App\Presenters;

use App\Components\SignInForm;

class SignPresenter extends BasePresenter
{

	protected function createComponentSignInForm(SignInForm $signInForm)
	{
		$form = $signInForm->create();
		$form->onSuccess[] = function () {
			$this->flashMessage('Uživatel byl přihlášen', 'success');
			$this->redirect(':Homepage:default');
		};

		return $form;
	}



	public function actionOut()
	{
		$this->user->logout();
		$this->redirect(':Homepage:default');
		$this->flashMessage('admin-sign.flashMessage.logoutSuccess', 'success');
	}

}