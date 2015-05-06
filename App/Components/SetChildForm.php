<?php

namespace App\Components;

use App\Model\Event\Entity;
use App\Model\Event\Facade;
use App\Model\Security\UserFacade;
use Nette\Application\UI\Form;
use Nette\Object;
use Nextras\Forms\Rendering\Bs3FormRenderer;

class SetChildForm extends Object
{

	/** @var UserFacade */
	private $userFacade;

	/** @var Facade\Child */
	private $childFacade;

	/** @var Entity\Performance */
	private $performance;

	/** @var Entity\Child */
	private $child;



	public function __construct(UserFacade $userFacade, Facade\Child $childFacade)
	{
		$this->userFacade = $userFacade;
		$this->childFacade = $childFacade;
	}



	public function setPerformance(Entity\Performance $performance)
	{
		$this->performance = $performance;
	}



	public function setChild(Entity\Child $child)
	{
		$this->child = $child;
	}



	public function create()
	{
		$teacherList = array();
		foreach ($this->userFacade->getUsersList() as $user) {
			$teacherList[$user->id] = $user->name;
		}

		$form = new Form();
		$form->addGroup($this->child ? "Upravit žáka" : "Přidat žáka");
		$form->addText("name", "Jméno:")
			->setRequired("Vyplňte prosím jméno");
		$form->addText("instrument", "Hudební nástroj:")
			->setRequired("Vyplňte prosím hudební nástroj");
		$form->addSelect("teacher", "Učitel", $teacherList)
			 ->setPrompt("-- Vyberte prosím učitele --")
			 ->setRequired("Vyberte prosím učitele");
		$form->addSubmit("send", $this->child ? "Upravit žáka" : "Přidat žáka");
		$form->setRenderer(new Bs3FormRenderer());

		$form->onSuccess[] = $this->processForm;
		if ($this->child) {
			$form->setDefaults(array(
				"name" => $this->child->name,
				"instrument" => $this->child->instrument,
				"teacher" => $this->child->teacher ? $this->child->teacher->id : NULL,
			));
		}

		return $form;
	}



	public function processForm(Form $form)
	{
		$values = $form->values;

		$teacher = $this->userFacade->findUserById($values->teacher);

		if ($this->child) {
			$child = $this->child;
			$child->teacher = $teacher;
		} else {
			$child = new Entity\Child($this->performance, $teacher);
		}
		$child->name = $values->name;
		$child->instrument = $values->instrument;
		$this->childFacade->save($child);
	}

}
