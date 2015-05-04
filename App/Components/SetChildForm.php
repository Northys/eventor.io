<?php

namespace App\Components;

use App\Model\Event\Entity;
use App\Model\Event\Facade;
use Nette\Application\UI\Form;
use Nette\Object;

class SetChildForm extends Object
{

	/** @var Facade\Child */
	private $childFacade;

	/** @var Entity\Event */
	private $event;

	/** @var Entity\Child */
	private $child;



	public function __construct(Facade\Child $childFacade)
	{
		$this->childFacade = $childFacade;
	}



	public function setEvent(Entity\Event $event)
	{
		$this->event = $event;
	}



	public function setChild(Entity\Child $child)
	{
		$this->child = $child;
	}



	public function create()
	{
		$form = new Form();
		$form->addGroup($this->child ? "Upravit žáka" : "Přidat žáka");
		$form->addText("name", "Jméno:");
		$form->addSubmit("send", $this->child ? "Upravit žáka" : "Přidat žáka");

		$form->onSuccess[] = $this->processForm;

		return $form;
	}



	public function processForm(Form $form)
	{
		$values = $form->values;
		$child = $this->child ? $this->child : new Entity\Child($this->event);
		$child->name = $values->name;
		$this->childFacade->save($child);
	}

}