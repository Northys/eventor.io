<?php

namespace App\Presenters;

use App\Components\SetChildForm;
use App\Components\SetEventForm;
use App\Components\SetPerformanceForm;
use App\Model\Event\Entity;
use App\Model\Event\Facade;
use App\Model\Pdf\PdfGenerator;
use App\Model\Pdf\Template\EventReportFactory;
use Kdyby\Doctrine\EntityManager;
use Nette\Application\Responses\FileResponse;
use Nette\Iterators\CachingIterator;
use Nette\Utils\Random;

/**
 * Class EventPresenter
 */
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

	/** @var EntityManager @autowire */
	public $entityManager;

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


	public function renderDefault()
	{
		$this->template->eventList = $this->eventFacade->getEventList();
	}


	/**
	 * @param \App\Components\SetEventForm $factory
	 *
	 * @return \Nette\Application\UI\Form
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


	/**
	 * @param $id
	 */
	public function actionDetail($id)
	{
		if (!$id or !($this->selectedEvent = $this->eventFacade->findEventById($id))) {
			$this->setView("notFound");
		}
	}


	/**
	 * @param \App\Components\SetPerformanceForm $factory
	 *
	 * @return \Nette\Application\UI\Form
	 */
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


	/**
	 * @param \App\Components\SetEventForm $factory
	 *
	 * @return \Nette\Application\UI\Form
	 */
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


	/**
	 * @param $id
	 * @param $performanceId
	 */
	public function actionPerformanceDetail($id, $performanceId)
	{
		if (!$id or !$performanceId or !($this->selectedEvent = $this->eventFacade->findEventById($id)) or !($this->selectedPerformance = $this->performanceFacade->findPerformanceById($performanceId))) {
			$this->setView("notFound");
		}
	}


	/**
	 * @param \App\Components\SetPerformanceForm $factory
	 *
	 * @return \Nette\Application\UI\Form
	 */
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


	/**
	 * @param \App\Components\SetChildForm $factory
	 *
	 * @return \Nette\Application\UI\Form
	 */
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


	/**
	 * @param $id
	 * @param $performanceId
	 * @param $childId
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


	/**
	 * @param \App\Components\SetChildForm $factory
	 *
	 * @return \Nette\Application\UI\Form
	 */
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


	/**
	 * @param $id
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


	/**
	 * @param $id
	 *
	 * @throws \PHPExcel_Reader_Exception
	 */
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


	/**
	 * @param $id
	 */
	public function handleDeleteEvent($id)
	{
		if ($id and $event = $this->eventFacade->findEventById($id)) {
			$this->eventFacade->delete($event);
		}
		$this->flashMessage("Událost byla odebrána", "success");
		$this->redirect("this");
	}


	/**
	 * @param $id
	 * @param $performanceId
	 */
	public function handleDeletePerformance($id, $performanceId)
	{
		if ($id and $performanceId and $performance = $this->performanceFacade->findPerformanceById($performanceId)) {
			$this->performanceFacade->delete($performance);
		}
		$this->flashMessage("Představení bylo odebráno", "success");
		$this->redirect("this");
	}


	/**
	 * @param $id
	 * @param $performanceId
	 * @param $childId
	 */
	public function handleDeleteChild($id, $performanceId, $childId)
	{
		if ($id and $childId and $performanceId and $child = $this->childFacade->findChildById($childId)) {
			$this->childFacade->delete($child);
		}
		$this->flashMessage("Žák byl odebrán", "success");
		$this->redirect("this");
	}


	/**
	 * @param $id
	 * @param $performanceId
	 * @param $position
	 *
	 * @throws \Exception
	 */
	public function handleMovePerformance($id, $performanceId, $position)
	{
		if ($performanceId and $performance = $this->performanceFacade->findPerformanceById($performanceId)) {
			$performance->setPosition($position);
		}
		$this->entityManager->flush();
		$this->flashMessage("Představení bylo posunuto", "success");
		$this->redirect(":Event:detail", $id);
	}

}
