<?php


namespace App\Model\Event\Facade;

use App\Model\Event\Entity;
use Kdyby\Doctrine\EntityManager;
use Nette\Object;

class Event extends Object
{

	/** @var EntityManager */
	private $em;

	/** @var \Kdyby\Doctrine\EntityRepository */
	private $eventRepository;



	public function __construct(EntityManager $em)
	{
		$this->em = $em;
		$this->eventRepository = $em->getRepository(Entity\Event::getClassName());
	}



	/**
	 * @return Entity\Event[]
	 */
	public function getEventList()
	{
		return $this->eventRepository->findAll();
	}



	/**
	 * @param $id
	 * @return null|Entity\Event
	 */
	public function findEventById($id)
	{
		return $this->eventRepository->find($id);
	}



	/**
	 * @param Entity\Event $event
	 */
	public function save(Entity\Event $event)
	{
		foreach ($event->performances as $performance) {
			foreach ($performance->children as $child) {
				$this->em->persist($child);
			}
			$this->em->persist($performance);
		}
		$this->em->persist($event);
		$this->em->flush();
	}



	/**
	 * @param Entity\Event $event
	 */
	public function delete(Entity\Event $event)
	{
		foreach ($event->performances as $performance) {
			foreach ($performance->children as $child) {
				$this->em->remove($child);
			}
			$this->em->remove($performance);
		}
		$this->em->remove($event);
		$this->em->flush();
	}

}