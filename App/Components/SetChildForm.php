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
		$teacherList = [];
		foreach ($this->userFacade->getUsersList() as $user) {
			$teacherList[$user->id] = $user->name;
		}

		$form = new Form();
		$form->addGroup($this->child ? "Upravit žáka" : "Přidat žáka");
		$form->addText("name", "Jméno:")
			->setRequired("Vyplňte prosím jméno");
		$form->addSelect("instrument", "Hudební nástroj", [
			"klavír"           => "klavír",
			"zob. flétna"      => "zob. flétna",
			"flétna"           => "flétna",
			"klarinet"         => "klarinet",
			"saxofon"          => "saxofon",
			"trubka"           => "trubka",
			"baskřídlovka"     => "baskřídlovka",
			"trombon"          => "trombon",
			"tuba"             => "tuba",
			"bicí nástroje"    => "bicí nástroje",
			"zpěv"             => "zpěv",
			"housle"           => "housle",
			"kontrabas"        => "kontrabas",
			"kytara"           => "kytara",
			"cimbál"           => "cimbál",
			"LDO"              => "LDO",
			"safoxon" => "safoxon",
			"altsafoxon" => "altsafoxon",
			"tenorsaxofon" => "tenorsaxofon",
			"baritonsaxofon" => "baritonsaxofon",
			"sopránsaxofon" => "sopránsaxofon",
			"cimbálová muzika" => "cimbálová muzika",
			"dechová hudba"    => "dechová hudba",
			"komorní hra"      => "komorní hra",
			"komorní zpěv"     => "komorní zpěv",
			"žesťové kvinteto" => "žesťové kvinteto",
			"žesťové kvarteto" => "žesťové kvarteto",
			"duo zob. fléten"  => "duo zob. fléten",
			"trio zob. fléten" => "duo zob. fléten",
			"kytarové duo"     => "kytarové duo",
			"kytarové trio"    => "kytarové trio",
			"taneční obor"     => "taneční obor",
			"klarinetové duo"  => "klarinetové duo",

		])
			->setPrompt("-- Vyberte prosím nástroj --")
			->setRequired("Vyplňte prosím hudební nástroj");

		$form->addSelect("teacher", "Učitel", $teacherList)
			->setPrompt("-- Vyberte prosím učitele --");

		$form->addSelect("class", "Ročník:", [
			"PHV"      => "PHV",
			"1. roč. " => "1. roč. ",
			"2. roč. " => "2. roč. ",
			"3. roč. " => "3. roč. ",
			"4. roč. " => "4. roč. ",
			"5. roč. " => "5. roč. ",
			"6. roč. " => "6. roč. ",
			"7. roč. " => "7. roč. ",
			"1./II. "  => "1./II. ",
			"2./II. "  => "2./II. ",
			"3./II. "  => "3./II. ",
			"4./II. "  => "4./II. ",
			"j. h."    => "j. h. ",
		])
			->setPrompt("-- Vyberte prosím ročník --");
		$form->addSubmit("send", $this->child ? "Upravit žáka" : "Přidat žáka");
		$form->setRenderer(new Bs3FormRenderer());

		$form->onSuccess[] = $this->processForm;
		if ($this->child) {
			$form->setDefaults([
				"name"       => $this->child->name,
				"instrument" => $this->child->instrument,
				"teacher"    => $this->child->teacher ? $this->child->teacher->id : null,
				"class"      => $this->child->class,
			]);
		}

		return $form;
	}



	public function processForm(Form $form)
	{
		$values = $form->values;

		$teacher = ($values->teacher) ? $this->userFacade->findUserById($values->teacher) : null;

		if ($this->child) {
			$child = $this->child;
			$child->teacher = $teacher;
		} else {
			$child = new Entity\Child($this->performance, $teacher);
		}
		$child->name = $values->name;
		$child->instrument = $values->instrument;
		$child->class = $values->class;
		$this->childFacade->save($child);
	}

}
