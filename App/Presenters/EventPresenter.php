<?php

namespace App\Presenters;

use App\Components\SetChildForm;
use App\Components\SetEventForm;
use App\Model\Event\Entity;
use App\Model\Event\Facade;
use App\Model\Pdf\PdfGenerator;
use App\Model\Pdf\Template\EventReportFactory;
use Nette\Application\Responses\FileResponse;

class EventPresenter extends SecuredPresenter
{

	/** @var Facade\Event @autowire */
	public $eventFacade;

	/** @var Facade\Child @autowire */
	public $childFacade;

	/** @var EventReportFactory @autowire */
	public $eventReportFactory;

	/** @var PdfGenerator @autowire */
	public $pdfGenerator;

	/** @var Entity\Event */
	private $selectedEvent;



	public function startup()
	{
		parent::startup();
		$this->submenu->addSection("Akce");
		$this->submenu->addItem(":Event:default", "Seznam akcí");
		$this->submenu->addItem(":Event:addAction", "Přidat akci");
	}



	/*
	 *
	 * default
	 *
	 */



	public function renderDefault()
	{
		$this->template->eventList = $this->eventFacade->getEventList();
	}



	/*
	 *
	 * addEvent
	 *
	 */



	public function createComponentAddEventForm(SetEventForm $factory)
	{
		$form = $factory->create();
		$form->onSuccess[] = function () {
			$this->flashMessage("Událost byla přidána", "success");
			$this->redirect(":Event:default");
		};

		return $form;
	}



	/*
	 *
	 * detail
	 *
	 */



	public function actionDetail($id)
	{
		if (!$id or !($this->selectedEvent = $this->eventFacade->findEventById($id))) {
			$this->setView("notFound");
		}
	}



	public function createComponentAddChildForm(SetChildForm $factory)
	{
		$factory->setEvent($this->selectedEvent);
		$form = $factory->create();
		$form->onSuccess[] = function () {
			$this->flashMessage("Žák byl přidán", "success");
			$this->redirect("this");
		};

		return $form;
	}



	public function createComponentEditEventForm(SetEventForm $factory)
	{
		$factory->setEvent($this->selectedEvent);
		$form = $factory->create();
		$form->onSuccess[] = function () {
			$this->flashMessage("Událost byla upravena", "success");
			$this->redirect("this");
		};

		return $form;
	}



	public function renderDetail()
	{
		$this->template->event = $this->selectedEvent;
	}



	/*
	 *
	 * handlers
	 *
	 */



	public function handleDownloadReport($id)
	{
		if ($id and $event = $this->eventFacade->findEventById($id)) {
			$report = $this->eventReportFactory->create();
			$report->setEvent($event);
			$filename = $this->pdfGenerator->savePdf($report, "event-" . $event->id . ".pdf");
			$this->sendResponse(new FileResponse($filename));
		}
	}



	public function handleDeleteEvent($id)
	{
		if ($id and $event = $this->eventFacade->findEventById($id)) {
			$this->eventFacade->delete($event);
		}
		$this->flashMessage("Událost byla odebrána");
		$this->redirect("this");
	}



	public function handleDeleteChild($id, $childId)
	{
		if ($id and $childId and $child = $this->childFacade->findChildById($childId)) {
			$this->childFacade->delete($child);
		}
		$this->flashMessage("Žák byl odebrán");
		$this->redirect("this");
	}

}