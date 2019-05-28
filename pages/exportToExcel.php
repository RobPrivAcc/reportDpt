<?php
	error_reporting(E_ALL);
	ini_set('display_errors', TRUE);
	ini_set('display_startup_errors', TRUE);
	date_default_timezone_set('Europe/London');
	ini_set('max_input_vars', 9000);
	
	include("../class/classDate.php");
	
	require_once dirname(__FILE__) . '/../class/Excel/PHPExcel.php';
	
	
	$dt = new DATE();
	
	$fileName = $_POST["shop"];
	
	$statsArray = 	array("Category" => json_decode($_POST['typesArray'], true),
					      "Sub category" => json_decode($_POST['subTypeArray'], true),
						  "Products" => json_decode($_POST['productsArray'], true));
	
	$objPHPExcel = new PHPExcel();
	
	$objPHPExcel->getProperties()->setCreator("Robert Kocjan")
								 ->setLastModifiedBy("Robert Kocjan")
								 ->setTitle("Sale by Department Raport");

/*
$a = json_decode($_POST['typesArray']);


	foreach($a as $key => $value){
		echo $key."<br/>";
	}
	*/

	$cat = null;
	
	$types = array("Category","Sub category","Products");
	
	
	$index =0;
	foreach($statsArray as $key => $value){
		
		$objPHPExcel->createSheet($index);
		$objPHPExcel->setActiveSheetIndex($index)
				->setCellValue('A1', $key)
				->setCellValue('B1', $dt->getYear()['lastYear'])
				->setCellValue('C1', $dt->getYear()['currentYear'])
				->setCellValue('D1', "Growth");
			
		$columnWidth = 12;
		 
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(80);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth($columnWidth);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth($columnWidth);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth($columnWidth);
	
		$objPHPExcel->getActiveSheet()->getStyle('A1:D1')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->setTitle($key)->getStyle('A1:D1')->getAlignment()->setWrapText(TRUE);
		$objPHPExcel->getActiveSheet()->getStyle('A1:D1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		
		
		$cellNo = 2;
		
		print_r($statsArray);
		
		foreach($value as $k => $v){
			if($key == "Category"){
				$cat = $k;
			}

			$objPHPExcel->setActiveSheetIndex($index)->setCellValue('A'.$cellNo, $k);
			$objPHPExcel->setActiveSheetIndex($index)->setCellValue('B'.$cellNo, $v[$dt->getDates()['prevYear']['year']]);
			$objPHPExcel->setActiveSheetIndex($index)->setCellValue('C'.$cellNo, $v[$dt->getDates()['currYear']['year']]);
			$objPHPExcel->setActiveSheetIndex($index)->setCellValue('D'.$cellNo, $v['growth']);
			$objPHPExcel->getActiveSheet()->getStyle('D'.$cellNo)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			
			$cellNo++;
		}
		
		$index++;
	}

	$objPHPExcel->setActiveSheetIndex(0);
	
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		
	$fileName .= '_'.$cat.'_'.$dt->getCurrentYearDate()['dateEnd'].'.xlsx';
	
	$fileName_to_save = $fileName;
	
	//$objWriter->save('../files/'.$fileName_to_save);
	$directory = explode("\\",dirname(dirname(__FILE__)));
	
	
	$pathToFile = dirname(pathinfo(__FILE__)['dirname']).'\\files\\'.$fileName;
	$linkToFile = str_replace("\\","\\\\",$pathToFile);
	
		
	$objWriter->save('../files/'.$fileName_to_save);
	
	if (file_exists($pathToFile)){
		$show = "<br/><div class='row'>";
			$show .= "<div class='col-xs-12 col-12'>";
				$show .= "<a href = '/".$directory[count($directory)-1]."/files/".$fileName."'  class='btn btn-primary'><i class='fa fa-download' aria-hidden='true'></i>  Download <b>".$fileName."</b></a>";
			$show .= "</div>";
		$show .= "</div><br/>";
	}else{
		 echo "Ups.. something went wrong and file wasn't created. Contact Robert.";    
	}

	echo $show;
?>