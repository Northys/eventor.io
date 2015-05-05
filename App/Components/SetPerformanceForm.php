<?php

namespace App\Components;

use App\Model\Event\Entity;
use App\Model\Event\Facade;
use Nette\Application\UI\Form;
use Nette\Object;
use Nextras\Forms\Rendering\Bs3FormRenderer;

class SetPerformanceForm extends Object
{

	/** @var Facade\Child */
	private $performanceFacade;

	/** @var Entity\Event */
	private $event;

	/** @var Entity\Child */
	private $performance;



	public function __construct(Facade\Performance $performanceFacade)
	{
		$this->performanceFacade = $performanceFacade;
	}



	public function setEvent(Entity\Event $event)
	{
		$this->event = $event;
	}



	public function setPerformance(Entity\Child $performance)
	{
		$this->performance = $performance;
	}



	public function create()
	{
		$form = new Form();
		$form->addGroup($this->performance ? "Upravit představení" : "Přidat představení");
		$form->addText("songAuthor", "Autor skladby:");
		$form->addText("songName", "Jméno skladby:");
		$form->addSubmit("send", $this->performance ? "Upravit představení" : "Přidat představení");
		$form->setRenderer(new Bs3FormRenderer());

		$form->onSuccess[] = $this->processForm;

		return $form;
	}



	public function processForm(Form $form)
	{
		$values = $form->values;
		$performance = $this->performance ? $this->performance : new Entity\Performance($this->event);
		$performance->songAuthor = $values->songAuthor;
		$performance->songName = $values->songName;
		$this->performanceFacade->save($performance);
	}

}
