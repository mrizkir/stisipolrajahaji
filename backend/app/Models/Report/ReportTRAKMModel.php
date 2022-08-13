<?php

namespace App\Models\Report;

use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use \PhpOffice\PhpSpreadsheet\Cell\DataType;

use App\Models\Akademik\KRSModel;

use App\Helpers\HelperNilai;
use App\Helpers\HelperKeuangan;

class ReportTRAKMModel extends ReportModel
{   
  public function __construct($dataReport)
  {
	  parent::__construct($dataReport); 
  }    
  public function printtoexcel1()  
  {
    $helper_nilai = new HelperNilai();
		$tahun_akademik = $this->dataReport['tahun_akademik'];
		$prodi_id = $this->dataReport['prodi_id'];
		$nama_prodi = strtoupper($this->dataReport['nama_prodi']);
		$semester_akademik = $this->dataReport['semester_akademik'];    
		$nama_semester = $helper_nilai->getSemester($semester_akademik);    

		$this->spreadsheet->getProperties()->setTitle("Report Aktivitas Kuliah MHS");
		$this->spreadsheet->getProperties()->setSubject("Report Aktivitas Kuliah MHS");

		$sheet = $this->spreadsheet->getActiveSheet();        
		$sheet->setTitle ('REKAP');

		$sheet->getParent()->getDefaultStyle()->applyFromArray([
			'font' => [
				'name' => 'Arial',
				'size' => '9',
			],
		]);

		$row=2;
		$sheet->mergeCells("A$row:I$row");				                
		$sheet->setCellValue("A$row","LAPORAN AKTIVITMAS KULIAH MAHASISWA");

		$row+=1;
		$sheet->mergeCells("A$row:I$row");		
		$sheet->setCellValue("A$row","PROGRAM STUDI $nama_prodi TAHUN AKADEMIK $tahun_akademik SEMESTER $nama_semester"); 
		
		$styleArray=array( 
			'font' => array('bold' => true,'size'=>'11'),
			'alignment' => array('horizontal'=>Alignment::HORIZONTAL_CENTER,
								'vertical'=>Alignment::HORIZONTAL_CENTER),								
		);               
		
		$sheet->getStyle("A1:A$row")->applyFromArray($styleArray);

		$sheet->getRowDimension(26)->setRowHeight(22);
		$sheet->getColumnDimension('A')->setWidth(7);
		$sheet->getColumnDimension('B')->setWidth(20);
		$sheet->getColumnDimension('C')->setWidth(50);
		$sheet->getColumnDimension('D')->setWidth(15);
		$sheet->getColumnDimension('E')->setWidth(15);
		$sheet->getColumnDimension('F')->setWidth(14);
		$sheet->getColumnDimension('G')->setWidth(14);        
		$sheet->getColumnDimension('H')->setWidth(14);        
		$sheet->getColumnDimension('I')->setWidth(20);        
		
		$row+=2;        
		$sheet->setCellValue("A$row",'NO');        
		$sheet->setCellValue("B$row",'NIM');    
		$sheet->setCellValue("C$row",'NAMA MAHASISWA');    
		$sheet->setCellValue("D$row",'ANGK.');    
		$sheet->setCellValue("E$row",'STATUS');    
		$sheet->setCellValue("F$row",'SKS');
		$sheet->setCellValue("G$row",'IPS');    
		$sheet->setCellValue("H$row",'IPK');    
		$sheet->setCellValue("I$row",'SPP');    
		

		$styleArray=array(
			'font' => array('bold' => true),
			'alignment' => array('horizontal'=>Alignment::HORIZONTAL_CENTER,
								'vertical'=>Alignment::HORIZONTAL_CENTER),
			'borders' => array('allBorders' => array('borderStyle' =>Border::BORDER_THIN))
		);
		$sheet->getStyle("A$row:I$row")->applyFromArray($styleArray);
		$sheet->getStyle("A$row:I$row")->getAlignment()->setWrapText(true);

		$data = \DB::table('v_datamhs AS A')
      ->select(\DB::raw('
        A.nim,
        A.nama_mhs,
        B.k_status,
        A.tahun_masuk,
        B.idkelas,
        C.n_status,
        0 AS sks,
        0 AS ips,
        0 AS ipk,
        0 AS spp
      '))
      ->join('dulang AS B', 'B.nim', 'A.nim')
      ->leftJoin('status_mhs AS C', 'B.k_status', 'C.k_status')
      ->where('B.idsmt', $semester_akademik)
      ->where('B.tahun', $tahun_akademik)
      ->where('A.kjur', $prodi_id)
      ->orderBy('A.tahun_masuk', 'ASC')
      ->orderBy('A.nama_mhs', 'ASC')
      ->get();      
      
    $data->transform(function ($item, $key) use ($tahun_akademik, $semester_akademik) {      
      $helper = new HelperNilai();
      $helper->setDataMHS([
        'nim' => $item->nim
      ]);

      
      $helper->getKHS($tahun_akademik, $semester_akademik);
      $item->ips = $helper->getIPS();
      $item->sks = $helper->getTotalSKS();
      $dataipk = $helper->getIPKSampaiTASemester($tahun_akademik, $semester_akademik, 'ipksks');	                                
      $item->ipk = $dataipk['ipk'];

      $helper = new HelperKeuangan();
      $helper->setDataMHS([
        'nim' => $item->nim,
        'tahun_masuk' => $item->tahun_masuk,
        'idsmt' => $semester_akademik,
        'idkelas' => $item->idkelas,
      ]);

      $spp = $helper->getTotalBiayaMhsPeriodePembayaran('lama');
      $item->spp = $helper->formatUang($spp);
    
      return $item;
    });
    
		$row+=1;
		$row_awal=$row; 		
		$total_ipk=0;
		$total_ips=0;
		$total_mhs=0;
		foreach ($data as $key=>$v)
		{
			$sheet->setCellValue("A$row", $key + 1);
			$sheet->setCellValueExplicit("B$row", $v->nim, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);			
      $sheet->setCellValue("C$row", $v->nama_mhs);
      $sheet->setCellValue("D$row", $v->tahun_masuk);
      $sheet->setCellValue("E$row", $v->n_status);
      $sheet->setCellValue("F$row", $v->sks);
      $sheet->setCellValue("G$row", $v->ips);
      $sheet->setCellValue("H$row", $v->ipk);
      $sheet->setCellValueExplicit("I$row", $v->spp, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
			$row += 1;			
      $total_ipk += $v->ipk;
		  $total_ips += $v->ips;		
      $total_mhs += 1;	
		}
		$row-=1;
		$styleArray=array(								
			'alignment' => array('horizontal'=>Alignment::HORIZONTAL_CENTER,
								'vertical'=>Alignment::HORIZONTAL_CENTER),
			'borders' => array('allBorders' => array('borderStyle' =>Border::BORDER_THIN))
		);   																					 
		$sheet->getStyle("A$row_awal:I$row")->applyFromArray($styleArray);
		$sheet->getStyle("A$row_awal:I$row")->getAlignment()->setWrapText(true);

		$styleArray=array(								
			'alignment' => array('horizontal'=>Alignment::HORIZONTAL_LEFT)
		);																					 
		$sheet->getStyle("C$row_awal:C$row")->applyFromArray($styleArray);    

		$row+=1;
		$row_awal_mhs=$row;
		$sheet->mergeCells("E$row:F$row");				                
		$sheet->setCellValue("E$row",'RATA-RATA IPS');
		$sheet->setCellValue("I$row", $helper_nilai->formatPecahan($total_ips, $total_mhs));

		$row+=1;
		$row_awal_mhs=$row;
		$sheet->mergeCells("E$row:F$row");				                
		$sheet->setCellValue("E$row",'RATA-RATA IPK');
		$sheet->setCellValue("I$row", $helper_nilai->formatPecahan($total_ipk, $total_mhs));
		
		$styleArray=array(
			'font' => array('bold' => true)
		);
		$sheet->getStyle("F$row_awal_mhs:I$row")->applyFromArray($styleArray);

		$generate_date=date('Y-m-d_H_m_s');
		return $this->excel("/report_trakm_$generate_date.xlsx");
  }      
}