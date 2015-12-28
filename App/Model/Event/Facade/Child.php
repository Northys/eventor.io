<?php

namespace App\Model\Event\Facade;

use App\Model\Event\Entity;
use Kdyby\Doctrine\EntityManager;
use Nette\Object;

/**
 * Class Child
 */
class Child extends Object
{

	/** @var EntityManager */
	private $em;

	/** @var \Kdyby\Doctrine\EntityRepository */
	private $childRepository;


	/**
	 * Child constructor.
	 *
	 * @param \Kdyby\Doctrine\EntityManager $em
	 */
	public function __construct(EntityManager $em)
	{
		$this->em = $em;
		$this->childRepository = $em->getRepository(Entity\Child::getClassName());
	}


	/**
	 * @param \App\Model\Event\Entity\Event $event
	 *
	 * @return array
	 */
	public function findChildByEvent(Entity\Event $event)
	{
		return $this->childRepository->findBy(["event" => $event]);
	}


	/**
	 * @param $id
	 *
	 * @return null|object
	 */
	public function findChildById($id)
	{
		return $this->childRepository->find($id);
	}


	/**
	 * @param \App\Model\Event\Entity\Child $child
	 *
	 * @throws \Exception
	 */
	public function save(Entity\Child $child)
	{
		$this->em->persist($child);
		$this->em->flush();
	}


	/**
	 * @param \App\Model\Event\Entity\Child $child
	 *
	 * @throws \Exception
	 */
	public function delete(Entity\Child $child)
	{
		$this->em->remove($child);
		$this->em->flush();
	}

}
