<?php

namespace App\Presenters;

use App\Components\SetChildForm;
use App\Components\SetEventForm;
use App\Components\SetPerformanceForm;
use App\Model\Event\Entity;
use App\Model\Event\Facade;
use App\Model\Pdf\PdfGenerator;
use App\Model\Pdf\Template\EventReportFactory;
use App\Model\Priority\Sorter;
use Nette\Application\Responses\FileResponse;
use Nette\Iterators\CachingIterator;
use Nette\Utils\Random;

class EventPresenter extends SecuredPresenter
{

	/** @var Facade\Event @autowire */
	public $eventFacade;

	/** @var Facade\Performance @autowire */
	public $performanceFacade;

	/** @var Facade\Child @autowire */
	public $childFacade;

	/** @var EventReportFactory @autowire */
	public $eventReportFactory;

	/** @var PdfGenerator @autowire */
	public $pdfGenerator;

	/** @var Sorter @autowire */
	public $prioritySorter;

	/** @var Entity\Event */
	private $selectedEvent;

	/** @var Entity\Performance */
	private $selectedPerformance;

	/** @var Entity\Child */
	private $selectedChild;



	public function startup()
	{
		parent::startup();
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



	public function createComponentAddPerformanceForm(SetPerformanceForm $factory)
	{
		$factory->setEvent($this->selectedEvent);
		$form = $factory->create();
		$form->onSuccess[] = function () {
			$this->flashMessage("Představení bylo přidáno", "success");
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
	 * performanceDetail
	 *
	 */


	public function actionPerformanceDetail($id, $performanceId)
	{
		if (!$id or !$performanceId or !($this->selectedEvent = $this->eventFacade->findEventById($id)) or !($this->selectedPerformance = $this->performanceFacade->findPerformanceById($performanceId))) {
			$this->setView("notFound");
		}
	}



	public function createComponentEditPerformanceForm(SetPerformanceForm $factory)
	{
		$factory->setEvent($this->selectedEvent);
		$factory->setPerformance($this->selectedPerformance);
		$form = $factory->create();
		$form->onSuccess[] = function () {
			$this->flashMessage("Představení bylo upraveno", "success");
			$this->redirect("this");
		};

		return $form;
	}



	public function createComponentAddChildForm(SetChildForm $factory)
	{
		$factory->setPerformance($this->selectedPerformance);
		$form = $factory->create();
		$form->onSuccess[] = function () {
			$this->flashMessage("Žák byl přídán", "success");
			$this->redirect("this");
		};

		return $form;
	}



	public function renderPerformanceDetail()
	{
		$this->template->performance = $this->selectedPerformance;
		$this->template->event = $this->selectedPerformance->event;
	}



	/*
	 *
	 * editChild
	 *
	 */


	public function actionEditChild($id, $performanceId, $childId)
	{
		if (!$id or !$childId or !$performanceId or
			!($this->selectedEvent = $this->eventFacade->findEventById($id)) or
			!($this->selectedPerformance = $this->performanceFacade->findPerformanceById($performanceId)) or
			!($this->selectedChild = $this->childFacade->findChildById($childId))
		) {
			$this->setView("notFound");
		}
	}



	public function createComponentEditChildForm(SetChildForm $factory)
	{
		$factory->setPerformance($this->selectedPerformance);
		$factory->setChild($this->selectedChild);
		$form = $factory->create();
		$form->onSuccess[] = function () {
			$this->flashMessage("Žák byl upraven", "success");
			$this->redirect(":Event:performanceDetail", ["id" => $this->selectedEvent->id, "performanceId" => $this->selectedPerformance->id]);
		};

		return $form;
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



	public function handleDownloadExcel($id)
	{
		if ($id and $event = $this->eventFacade->findEventById($id)) {
			$performances = [];
			$performances[1] = [
				"A" => "#",
				"B" => "Autor skladby",
				"C" => "Název skladby",
				"D" => "Poznámka",
				"E" => "Žáci",
				"F" => "Nástroj",
				"G" => "Ročník",
			];
			/** @var Entity\Performance $performance */
			foreach ($event->performances as $performance) {
				foreach ($iterator = new CachingIterator($performance->children) as $child) {
					$tmpRow = [];
					if ($iterator->isFirst()) {
						$tmpRow["A"] = $iterator->counter;
						$tmpRow["B"] = $performance->songAuthor;
						$tmpRow["C"] = $performance->songName;
						$tmpRow["D"] = $performance->note;
					}
					$tmpRow["E"] = $child->name;
					$tmpRow["F"] = $child->instrument;
					$tmpRow["G"] = $child->class;
					$tmpRow["H"] = $child->teacher->name;
					$performances[] = $tmpRow;
				}
			}


			$excel = new \PHPExcel();
			$sheet = $excel->getActiveSheet();
			foreach ($performances as $row => $invitation) {
				foreach ($invitation as $cell => $value) {
					$sheet->setCellValue($cell . $row, trim($value));
				}
			}
			$writer = \PHPExcel_IOFactory::createWriter($excel, "Excel2007");
			$name = $this->context->getParameters()["tempDir"] . "/export/" . Random::generate();
			if (!file_exists(dirname($name))) {
				mkdir(dirname($name));
			}
			$writer->save($name);
			$this->sendResponse(new FileResponse($name, "udalost-" . $event->id . ".xlsx"));
		}
	}



	public function handleDeleteEvent($id)
	{
		if ($id and $event = $this->eventFacade->findEventById($id)) {
			$this->eventFacade->delete($event);
		}
		$this->flashMessage("Událost byla odebrána", "success");
		$this->redirect("this");
	}



	public function handleDeletePerformance($id, $performanceId)
	{
		if ($id and $performanceId and $performance = $this->performanceFacade->findPerformanceById($performanceId)) {
			$this->performanceFacade->delete($performance);
		}
		$this->flashMessage("Představení bylo odebráno", "success");
		$this->redirect("this");
	}



	public function handleDeleteChild($id, $performanceId, $childId)
	{
		if ($id and $childId and $performanceId and $child = $this->childFacade->findChildById($childId)) {
			$this->childFacade->delete($child);
		}
		$this->flashMessage("Žák byl odebrán", "success");
		$this->redirect("this");
	}



	public function handleChangeOrder($id, $childId, $direction)
	{
		if ($childId and $menuItem = $this->performanceFacade->findPerformanceById($childId)) {
			if ($direction === "up") {
				$this->prioritySorter->moveUp($menuItem);
			} elseif ($direction === "down") {
				$this->prioritySorter->moveDown($menuItem);
			}
		}
		$this->flashMessage("Představení bylo posunuto " . ($direction === "up" ? "nahoru" : "dolu"), "success");
		$this->redirect(":Event:detail", $id);
	}

}
