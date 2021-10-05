<?php

namespace App\Helpers;

class HelperAkademik {      
	/**
	 * daftar semester
	 */
	public static $semester=[
		1=>'GANJIL',
		2=>'GENAP',
		3=>'PENDEK'
	];
	/**
	 * daftar semester matakuliah
	 * @var type 
	 */
	public static $SemesterMatakuliah = [1=>1,2=>2,3=>3,4=>4,5=>5,6=>6,7=>7,8=>8];
	/**
	 * daftar semester matakuliah bentuk romawi
	 * @var type 
	 */
	public static $SemesterMatakuliahRomawi = [1=>'I',2=>'II',3=>'III',4=>'IV',5=>'V',6=>'VI',7=>'VII',8=>'VIII'];
	/**
	 * daftar sks matakuliah
	 * @var type 
	 */
	public static $sks = [0=>'0',1=>'1',2=>'2',3=>'3',4=>'4',5=>'5',6=>'6',7=>'7',8=>'8',9=>'9',10=>'10',11=>'11',12=>'12'];   

	public static $skala_penilaian=[
		'A',		       
		'B',		
		'C',		
		'D',
		'E'
	];

	public static $nilai_mutu=[
		'A'=>4,		        
		'B'=>3,		
		'C'=>2,		
		'D'=>1,
		'E'=>0
	];
	public static function getSemester($id=null)
	{
		if ($id===null)
		{
			return HelperAkademik::$semester;
		}
		else if (isset(HelperAkademik::$semester[$id]))
		{
			return HelperAkademik::$semester[$id];
		}
		else
		{
			return null;
		}
	}
	public static function getSkalaPenilaian()
	{
		return HelperAkademik::$skala_penilaian;
	}
	public static function getNilaiMutu ($n_kual)
	{
		if (isset(HelperAkademik::$nilai_mutu[$n_kual]))
		{
			return HelperAkademik::$nilai_mutu[$n_kual];
		}
		else
		{
			return null;
		}
	}
	public static function getNilaiHuruf ($n_kuan)
	{
		if (is_null($n_kuan))
		{
			$n_kual = 'N.A';
		} else if ($n_kuan >= 4)
		{
			$n_kual = "A";
		
		} else if ($n_kuan >= 3 && $n_kuan < 4)
		{
			$n_kual = "B";
		} else if ($n_kuan >= 2 && $n_kuan < 3)		
		{
			$n_kual = "C";
		} else if ($n_kuan >= 1 && $n_kuan < 2)		
		{
			$n_kual = "D";
		} else if ($n_kuan < 1)
		{
			$n_kual = "E";
		}
		return $n_kual;
	}
	/**
	* digunakan untuk mem-format persentase
	*/
	public static function formatIPK ($m,$sks) {
		$result=0.00;
		if ($m > 0 && $sks > 0) {

			$temp=($m/$sks);
			if ($temp == 40)
			{
				$result = '4.00';
			}
			else if ($temp == 30)
			{
				$result = '3.00';
			}
			else if ($temp == 20)
			{
				$result = '2.00';
			}
			else if ($temp == 10)
			{
				$result = '1.00';
			}
			else 
			{
				$result = number_format($temp/10,2);
			}            
		}                     
		return $result;
	}
}