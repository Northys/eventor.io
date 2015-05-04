<?php

namespace App\Model;

use Brabijan\SeoComponents\DI\ITargetSectionProvider;
use Brabijan\SeoComponents\Router\Target;
use Brabijan\SeoComponents\TargetSection;
use Nette\Object;

class StaticTargetList extends Object implements ITargetSectionProvider
{

	/**
	 * @return TargetSection
	 */
	public function getTargetSection()
	{
		$section = new TargetSection("Static target list");
		$section->addTarget("Homepage", new Target("Homepage", "default"));

		return $section;
	}

}