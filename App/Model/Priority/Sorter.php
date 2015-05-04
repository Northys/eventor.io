<?php

namespace App\Model\Priority;

use Doctrine\ORM\Query;
use Kdyby\Doctrine\EntityManager;
use Nette\InvalidArgumentException;
use Nette\Object;
use Nette\Reflection\ClassType;

class Sorter extends Object
{

	/** @var EntityManager */
	private $entityManager;



	public function __construct(EntityManager $entityManager)
	{
		$this->entityManager = $entityManager;
	}



	/**
	 * @param $entity
	 * @param callable $modifier
	 */
	public function moveUp($entity, $modifier = NULL)
	{
		$qb = $this->createBaseQuery($entity, $modifier);
		$qb->andWhere("e.priority < :currentPriority")->setParameter('currentPriority', $entity->priority);
		$qb->orderBy(new Query\Expr\OrderBy("e.priority", "DESC"))->setMaxResults(1);

		$result = $qb->getQuery()->execute();
		if (!empty($result)) {
			$this->switchEntities($entity, $result[0]);
		}
	}



	/**
	 * @param $entity
	 * @param callable $modifier
	 */
	public function moveDown($entity, $modifier = NULL)
	{
		$qb = $this->createBaseQuery($entity, $modifier);
		$qb->andWhere("e.priority > :currentPriority")->setParameter('currentPriority', $entity->priority);
		$qb->orderBy(new Query\Expr\OrderBy("e.priority", "ASC"));
		$result = $qb->getQuery()->execute();
		if (!empty($result)) {
			$this->switchEntities($entity, $result[0]);
		}
	}



	/**
	 * @param $entity
	 * @param callable $modifier
	 * @return \Kdyby\Doctrine\QueryBuilder
	 */
	private function createBaseQuery($entity, $modifier)
	{
		$reflection = ClassType::from($entity);
		if (!$reflection->hasProperty('priority')) {
			throw new InvalidArgumentException('Entity has not property $priority');
		}

		$qb = $this->entityManager->getRepository($reflection->getName())->createQueryBuilder("e")->select();
		if ($modifier !== NULL) {
			$modifier($qb);
		}
		$qb->setMaxResults(1);

		return $qb;
	}



	/**
	 * @param $entity1
	 * @param $entity2
	 */
	private function switchEntities($entity1, $entity2)
	{
		$x = $entity1->priority;
		$entity1->priority = $entity2->priority;
		$entity2->priority = $x;

		$this->entityManager->persist($entity1, $entity2);
		$this->entityManager->flush();
	}

} 