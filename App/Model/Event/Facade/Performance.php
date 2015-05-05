<?php

namespace App\Model\Event\Facade;

use App\Model\Event\Entity;
use Kdyby\Doctrine\EntityManager;
use Nette\Object;

class Performance extends Object
{

	/** @var EntityManager */
	private $em;

	/** @var \Kdyby\Doctrine\EntityRepository */
	private $performanceRepository;



	public function __construct(EntityManager $em)
	{
		$this->em = $em;
		$this->performanceRepository = $em->getRepository(Entity\Performance::getClassName());
	}



	/**
	 * @param $id
	 * @return null|Entity\Performance
	 */
	public function findPerformanceById($id)
	{
		return $this->performanceRepository->find($id);
	}



	/**
	 * @param Entity\Performance $performance
	 */
	public function save(Entity\Performance $performance)
	{
		$this->em->persist($performance);
		$this->em->flush();
	}



	/**
	 * @param Entity\Performance $performance
	 */
	public function delete(Entity\Performance $performance)
	{
		$this->em->remove($performance);
		$this->em->flush();
	}

}