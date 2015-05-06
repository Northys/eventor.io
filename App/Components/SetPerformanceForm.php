<?php

namespace App\Components;

use App\Model\Event\Entity;
use App\Model\Event\Facade;
use Nette\Application\UI\Form;
use Nette\Object;

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



	public function setPerformance(Entity\Performance $performance)
	{
		$this->performance = $performance;
	}



	public function create()
	{
		$form = new Form();
		$form->addGroup($this->performance ? "Upravit představení" : "Přidat představení");
		$form->addTextArea("songAuthor", "Autor skladby:")
			 ->setRequired("Vyplňte prosím autora skladby");
		$form->addTextArea("songName", "Jméno skladby:")
			 ->setRequired("Vyplňte prosím jméno skladby");
		$form->addTextArea("note", "Poznámka:");
		$form->addSubmit("send", $this->performance ? "Upravit představení" : "Přidat představení");

		$form->onSuccess[] = $this->processForm;
		if ($this->performance) {
			$form->setDefaults(array(
				"songAuthor" => $this->performance->songAuthor,
				"songName" => $this->performance->songName,
				"note" => $this->performance->note,
			));
		}

		return $form;
	}



	public function processForm(Form $form)
	{
		$values = $form->values;
		$performance = $this->performance ? $this->performance : new Entity\Performance($this->event);
		$performance->songAuthor = $values->songAuthor;
		$performance->songName = $values->songName;
		$performance->note = $values->note;
		$this->performanceFacade->save($performance);
	}

}
