<?php

namespace App\Model\Pdf;

use Nette\Application\UI\ITemplate;

interface IPdfTemplate
{

	/** @return ITemplate */
	public function render();

}