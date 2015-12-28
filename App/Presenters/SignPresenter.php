<?php

namespace App\Presenters;

use App\Components\SignInForm;

/**
 * Class SignPresenter
 */
class SignPresenter extends BasePresenter
{

	/**
	 * @param \App\Components\SignInForm $signInForm
	 *
	 * @return \Nette\Application\UI\Form
	 */
	protected function createComponentSignInForm(SignInForm $signInForm)
	{
		$form = $signInForm->create();
		$form->onSuccess[] = function () {
			$this->flashMessage('Uživatel byl přihlášen', 'success');
			$this->redirect(':Event:default');
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
