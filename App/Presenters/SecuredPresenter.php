<?php

namespace App\Presenters;

/**
 * Class SecuredPresenter
 */
class SecuredPresenter extends BasePresenter
{

	protected function startup()
	{
		parent::startup();
		if (!$this->user->isLoggedIn()) {
			$this->redirect(":Sign:in");
		}
	}

}
