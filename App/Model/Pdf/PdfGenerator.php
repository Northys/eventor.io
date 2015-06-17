<?php

namespace App\Model\Pdf;

use Nette\Object;
use Nette\Utils\FileSystem;

class PdfGenerator extends Object
{

	/** @var string */
	private $documentStorage;



	public function __construct($documentStorage, $tempDir)
	{
		define("_MPDF_TEMP_PATH", $tempDir . "/cache/mPdf/tmp/");
		define('_MPDF_TTFONTDATAPATH', $tempDir . "/cache/mPdf/ttfontdata/");
		$this->documentStorage = $documentStorage;

		if (!file_exists(_MPDF_TEMP_PATH)) {
			FileSystem::createDir(_MPDF_TEMP_PATH);
		}

		if (!file_exists(_MPDF_TTFONTDATAPATH)) {
			FileSystem::createDir(_MPDF_TTFONTDATAPATH);
		}
	}



	public function savePdf(IPdfTemplate $pdfTemplate, $filename)
	{
		$filename = rtrim($this->documentStorage, "/") . "/" . $filename;
		$html = (string)$pdfTemplate->render();

		$mpdf = new \mPDF();
		$mpdf->WriteHTML($html);
		$mpdf->Output($filename, "F");

		return $filename;
	}

}
