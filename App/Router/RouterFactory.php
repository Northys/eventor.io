<?php

namespace App\Router;

use Nette;
use Nette\Application\Routers\Route;
use Nette\Application\Routers\RouteList;

/**
 * Class RouterFactory
 */
class RouterFactory
{

	/**
	 * @return \Nette\Application\IRouter
	 */
	public function createRouter()
	{
		$router = new RouteList();
		$router[] = $adminModule = new RouteList('Admin');
		$adminModule[] = $seoSettingsModule = new RouteList('SeoSettings');
		$seoSettingsModule[] = new Route('admin/seo/<presenter>[/<action>][/<id>]', 'Homepage:default');
		$adminModule[] = new Route('admin/<presenter>[/<action>][/<id>]', 'Homepage:default');
		$router[] = new Route('<presenter>[/<action>][/<id>]', 'Homepage:default');

		return $router;
	}

}
