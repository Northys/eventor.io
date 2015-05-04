<?php

namespace App\Model\Event\Facade;

use App\Model\Event\Entity;
use Kdyby\Doctrine\EntityManager;
use Nette\Object;

class Child extends Object
{

	/** @var EntityManager */
	private $em;

	/** @var \Kdyby\Doctrine\EntityRepository */
	private $childRepository;



	public function __construct(EntityManager $em)
	{
		$this->em = $em;
		$this->childRepository = $em->getRepository(Entity\Child::getClassName());
	}



	public function findChildByEvent(Entity\Event $event)
	{
		return $this->childRepository->findBy(array("event" => $event));
	}



	public function findChildById($id)
	{
		return $this->childRepository->find($id);
	}



	public function save(Entity\Child $child)
	{
		$this->em->persist($child);
		$this->em->flush();
	}



	public function delete(Entity\Child $child)
	{
		$this->em->remove($child);
		$this->em->flush();
	}

}