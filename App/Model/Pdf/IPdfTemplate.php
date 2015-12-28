<?php

namespace App\Model\Pdf;

use Nette\Application\UI\ITemplate;

/**
 * Interface IPdfTemplate
 *
 * @package App\Model\Pdf
 */
interface IPdfTemplate
{

	/** @return ITemplate */
	public function render();

}
