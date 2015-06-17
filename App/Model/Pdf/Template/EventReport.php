<?php

namespace App\Model\Pdf\Template;

use App\Model\Event\Entity\Event;
use App\Model\Pdf\IPdfTemplate;
use Nette\Application\UI\ITemplate;

class EventReport extends BaseTemplate implements IPdfTemplate
{

	/** @var Event */
	private $event;



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
		foreach ($this->event->performances as $performance) {
			foreach ($performance->children as $child) {
				if (!$child->teacher) {
					continue;
				}
				$teacherName = $child->teacher->name;
				$teachers[$teacherName][] = $performance->priority;
			}
		}

		$this->template->teacherSummary = $teachers;

		return $this->template;
	}

}


interface EventReportFactory
{

	/** @return EventReport */
	public function create();

}
