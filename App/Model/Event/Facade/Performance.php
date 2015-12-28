<?php

namespace App\Model\Event\Facade;

use App\Model\Event\Entity;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\NoResultException;
use Kdyby\Doctrine\EntityManager;
use Nette\Object;

/**
 * Class Performance
 */
class Performance extends Object
{

	/** @var EntityManager */
	private $em;

	/** @var \Kdyby\Doctrine\EntityRepository */
	private $performanceRepository;


	/**
	 * Performance constructor.
	 *
	 * @param \Kdyby\Doctrine\EntityManager $em
	 */
	public function __construct(EntityManager $em)
	{
		$this->em = $em;
		$this->performanceRepository = $em->getRepository(Entity\Performance::getClassName());
	}


	/**
	 * @param $id
	 *
	 * @return null|Entity\Performance
	 */
	public function findPerformanceById($id)
	{
		return $this->performanceRepository->find($id);
	}


	/**
	 * @param \App\Model\Event\Entity\Event $event
	 *
	 * @return array
	 */
	public function getNextPositionByEvent(Entity\Event $event)
	{
		$query = $this->em->createQueryBuilder()
			->select('MAX(p.position)')
			->from(Entity\Performance::class, 'p')
			->whereCriteria(['p.event' => $event])
			->getQuery();

		try {
			$result = $query->getResult(AbstractQuery::HYDRATE_SINGLE_SCALAR);
			$nextPosition = $result + 1;
		} catch (NoResultException $e) {
			$nextPosition = 1;
		}

		return $nextPosition;
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
