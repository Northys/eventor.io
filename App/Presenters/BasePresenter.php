<?php

namespace App\Presenters;

use Brabijan;
use Kdyby\Autowired\AutowireComponentFactories;
use Kdyby\Autowired\AutowireProperties;
use Nette\Application\UI\Presenter;

/**
 * Class BasePresenter
 */
class BasePresenter extends Presenter
{

	use AutowireProperties;
	use AutowireComponentFactories;

	/** @var Brabijan\BootstrapUIComponents\Submenu\Control */
	protected $submenu;



	public function __construct()
	{
		parent::__construct();
		$this->submenu = new Brabijan\BootstrapUIComponents\Submenu\Control;
	}



	public function beforeRender()
	{
		$this->template->renderSubmenu = $this->submenu->getVisibility();
	}



	public function createComponentSubmenu()
	{
		return $this->submenu;
	}

}
