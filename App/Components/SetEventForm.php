<?php

namespace App\Components;


use App\Model\Event\Entity;
use App\Model\Event\Facade;
use Nette\Application\UI\Form;
use Nette\Object;
use Nette\Security\User;
use Nette\Utils\DateTime;
use Nextras\Forms\Rendering\Bs3FormRenderer;

class SetEventForm extends Object
{

	/** @var Facade\Event */
	private $eventFacade;

	/** @var User */
	private $securityUser;

	/** @var Entity\Event */
	private $event;



	public function __construct(Facade\Event $eventFacade, User $securityUser)
	{
		$this->eventFacade = $eventFacade;
		$this->securityUser = $securityUser;
	}



	public function setEvent(Entity\Event $event)
	{
		$this->event = $event;
	}



	public function create()
	{
		$form = new Form();
		$form->addGroup($this->event ? "Upravit událost" : "Přidat událost");
		$form->addText("name", "Jméno:");
		$form->addText("date", "Datum:")->setAttribute('class', 'datepicker');
		$form->addText("place", "Místo:");
		$form->addTextArea("note", "Poznámka:");
		$form->addSubmit("send", $this->event ? "Upravit událost" : "Přidat událost");
		$form->setRenderer(new Bs3FormRenderer());

		$form->onSuccess[] = $this->processForm;
		if($this->event) {
			$form->setDefaults(array(
				"name" => $this->event->name,
				"date" => $this->event->date->format('Y-m-d'),
				"place" => $this->event->place,
			));
		}

		return $form;
	}



	public function processForm(Form $form)
	{
		$values = $form->values;
		$event = $this->event ? $this->event : new Entity\Event($this->securityUser->getIdentity());
		$event->name = $values->name;
		$event->date = DateTime::from($values->date);
		$event->place = $values->place;
		$event->note = $values->note;

		$this->eventFacade->save($event);
	}

}
