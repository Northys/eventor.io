<?php

namespace App\Components;

use App\Model\Event\Entity;
use App\Model\Event\Facade;
use App\Model\Security\UserFacade;
use Nette\Application\UI\Form;
use Nette\Object;

class SetChildForm extends Object
{

	/** @var UserFacade */
	private $userFacade;

	/** @var Facade\Child */
	private $childFacade;

	/** @var Entity\Event */
	private $event;

	/** @var Entity\Child */
	private $child;



	public function __construct(UserFacade $userFacade, Facade\Child $childFacade)
	{
		$this->userFacade = $userFacade;
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
		$teacherList = array();
		foreach ($this->userFacade->getUsersList() as $user) {
			$teacherList[$user->id] = $user->class . " - " . $user->name;
		}

		$form = new Form();
		$form->addGroup($this->child ? "Upravit žáka" : "Přidat žáka");
		$form->addText("name", "Jméno:");
		$form->addText("songAuthor", "Autor skladby:");
		$form->addText("songName", "Jméno skladby:");
		$form->addText("instrument", "Hudební nástroj:");
		$form->addSelect("teacher", "Třída", $teacherList)
			 ->setPrompt("-- Vyberte prosím učitele --");
		$form->addSubmit("send", $this->child ? "Upravit žáka" : "Přidat žáka");

		$form->onSuccess[] = $this->processForm;

		return $form;
	}



	public function processForm(Form $form)
	{
		$values = $form->values;

		$teacher = $this->userFacade->findUserById($values->teacher);

		$child = $this->child ? $this->child : new Entity\Child($this->event, $teacher);
		$child->name = $values->name;
		$child->songAuthor = $values->songAuthor;
		$child->songName = $values->songName;
		$child->instrument = $values->instrument;
		$this->childFacade->save($child);
	}

}