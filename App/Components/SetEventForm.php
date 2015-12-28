<?php

namespace App\Components;


use App\Model\Event\Entity;
use App\Model\Event\Facade;
use Nette\Application\UI\Form;
use Nette\Object;
use Nette\Security\User;
use Nette\Utils\DateTime;

/**
 * Class SetEventForm
 */
class SetEventForm extends Object
{

	/** @var Facade\Event */
	private $eventFacade;

	/** @var User */
	private $securityUser;

	/** @var Entity\Event */
	private $event;


	/**
	 * SetEventForm constructor.
	 *
	 * @param \App\Model\Event\Facade\Event $eventFacade
	 * @param \Nette\Security\User $securityUser
	 */
	public function __construct(Facade\Event $eventFacade, User $securityUser)
	{
		$this->eventFacade = $eventFacade;
		$this->securityUser = $securityUser;
	}


	/**
	 * @param \App\Model\Event\Entity\Event $event
	 */
	public function setEvent(Entity\Event $event)
	{
		$this->event = $event;
	}


	/**
	 * @return \Nette\Application\UI\Form
	 */
	public function create()
	{
		$form = new Form();
		$form->addGroup($this->event ? "Upravit událost" : "Přidat událost");
		$form->addText("name", "Jméno:")
			->setRequired("Vyplňte prosím jméno");
		$form->addText("date", "Datum:")
			->setAttribute('class', 'datepicker')
			->setRequired("Vyberte prosím datum");;
		$form->addText("place", "Místo:")
			->setRequired("Vyplňte prosím místo");
		$form->addTextArea("note", "Poznámka:");
		$form->addSubmit("send", $this->event ? "Upravit událost" : "Přidat událost");

		$form->onSuccess[] = $this->processForm;
		if ($this->event) {
			$form->setDefaults([
				"name"  => $this->event->name,
				"date"  => $this->event->date->format('Y-m-d'),
				"place" => $this->event->place,
				"note"  => $this->event->note,
			]);
		}

		return $form;
	}


	/**
	 * @param \Nette\Application\UI\Form $form
	 */
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
