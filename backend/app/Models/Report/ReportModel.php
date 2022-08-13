<?php
namespace App\Models\Report;

use Illuminate\Database\Eloquent\Model;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Csv;

use App\Helpers\Helper;

class ReportModel extends Model
{   
	/**
	* data report
	*/
	protected $dataReport = [];
   /**
	* object spreadsheet
	*/
	protected $spreadsheet;
	
	public function __construct($dataReport)
	{
		$this->dataReport = $dataReport;
		$this->spreadsheet = new Spreadsheet();         
	}
   /**
	 * digunakan untuk mengeset data report dan inisialisasi object spreadsheet
	 */
	public function setObjReport($dataReport)
	{   
		$this->dataReport = $dataReport;
		$this->spreadsheet = new Spreadsheet();         
	}
	public function excel(string $filename)
	{
		$pathToFile = Helper::exported_path('/excel').$filename;
		$this->spreadsheet->getProperties()->setCreator('portalekampus');
		$this->spreadsheet->getProperties()->setLastModifiedBy('portalekampus');         
		$writer = new Xlsx($this->spreadsheet);
		$writer->save($pathToFile);        
		return response()->download($pathToFile)->deleteFileAfterSend(false);
	}
	public function csv(string $filename)
	{
		$pathToFile = Helper::exported_path('/csv').$filename;
		$this->spreadsheet->getProperties()->setCreator('portalekampus');
		$this->spreadsheet->getProperties()->setLastModifiedBy('portalekampus');         
		$writer = new Csv($this->spreadsheet);
		$writer->setPreCalculateFormulas(false);
		$writer->setDelimiter(';');
		$writer->setEnclosureRequired(true);
		$writer->save($pathToFile);        
		return response()->download($pathToFile)->deleteFileAfterSend(false);
	}   
}
