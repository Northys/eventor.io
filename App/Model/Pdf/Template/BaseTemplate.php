<?php

namespace App\Model\Pdf\Template;

use Nette\Application\UI\ITemplate;
use Nette\Application\UI\ITemplateFactory;
use Nette\Object;

/**
 * @property ITemplate $template
 */
class BaseTemplate extends Object
{

	/** @var ITemplateFactory */
	private $templateFactory;

	/** @var ITemplate */
	private $template;



	public function injectPrimary(ITemplateFactory $templateFactory)
	{
		$this->templateFactory = $templateFactory;
	}



	/**
	 * @return ITemplate
	 */
	public function getTemplate()
	{
		if (!$this->template) {
			$this->template = $this->templateFactory->createTemplate();
		}

		return $this->template;
	}

}