<?php

namespace App\Model\Pdf\Template;

use App\Model\Event\Entity\Event;
use App\Model\Pdf\IPdfTemplate;
use Nette\Application\UI\ITemplate;

/**
 * Class EventReport
 */
class EventReport extends BaseTemplate implements IPdfTemplate
{

	/** @var Event */
	private $event;


	/**
	 * @param \App\Model\Event\Entity\Event $event
	 */
	public function setEvent(Event $event)
	{
		$this->event = $event;
	}



	/** @return ITemplate */
	public function render()
	{
		$this->template->setFile(__DIR__ . "/EventReport.latte");
		$this->template->event = $this->event;

		$teachers = [];
		$iterator = 0;
		foreach ($this->event->performances as $performance) {
			$iterator++;
			foreach ($performance->children as $child) {
				if (!$child->teacher) {
					continue;
				}
				$teacherName = $child->teacher->name;
				$teachers[$teacherName][] = $iterator;
			}
		}

		$this->template->teacherSummary = $teachers;

		return $this->template;
	}

}


/**
 * Interface EventReportFactory
 *
 * @package App\Model\Pdf\Template
 */
interface EventReportFactory
{

	/** @return EventReport */
	public function create();

}
