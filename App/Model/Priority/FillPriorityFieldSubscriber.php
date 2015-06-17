<?php

namespace App\Model\Priority;

use Doctrine\ORM\Event\PreFlushEventArgs;
use Kdyby\Events\Subscriber;
use Nette;

class FillPriorityFieldSubscriber extends Nette\Object implements Subscriber
{

	public function getSubscribedEvents()
	{
		return ['preFlush'];
	}



	public function preFlush(PreFlushEventArgs $args)
	{
		$em = $args->getEntityManager();
		$uow = $em->getUnitOfWork();
		foreach ($uow->getScheduledEntityInsertions() as $entity) {
			$reflection = Nette\Reflection\ClassType::from($entity);
			if ($reflection->hasProperty("priority")) {
				$repository = $em->getRepository($reflection->getName());
				$result = $repository->createQueryBuilder("p")->select("MAX(p.priority)")->getQuery()->execute();
				$entity->priority = ($result ? (int)$result[0][1] : 0) + 1;
			}
		}
	}

}
