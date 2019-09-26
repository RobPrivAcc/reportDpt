<?php
    session_start();
	error_reporting(E_ALL);
	ini_set('display_errors', TRUE);
	ini_set('display_startup_errors', TRUE);
	date_default_timezone_set('Europe/London');
	ini_set('max_input_vars', 9000);

    include("../class/classDate.php");
    require_once dirname(__FILE__) . '/../class/Excel/PHPExcel.php';

    $dt = new DATE();
echo $_SESSION['allShops'];
	$allShops = json_decode($_SESSION['allShops'], true);

	var_dump($allShops);

foreach ($allShops as $type => $stats){
    echo $type.'<br/>';
    foreach ($stats as $shop => $details){
        echo $details[$dt->getYear()['lastYear']].' '.$details[$dt->getYear()['currentYear']].'   ';
        //var_dump($details);
//        echo $details['2018'].'  ';
    }
    echo '<br/><br/><br/>';
}

//var_dump($allShops);
//echo count($allShops);

function addShopsStats($obiect, $curIndex){
    $obiect->getActiveSheet()->mergeCells('A18:E22');
}
	/*

	//echo $_POST['type'].'<br/>';
	$fileName = $_POST["shop"];
	$typeName = $_POST["type"];
	
	$statsArray = array("Category" => json_decode($_POST['typesArray'], true),
					    "Sub category" => json_decode($_POST['subTypeArray'], true),
						"Products" => json_decode($_POST['productsArray'], true));

//echo '<pre>' . var_export($statsArray, true) . '</pre>';
	
	echo "fileName = ".$_POST["shop"]."<br/>";
	echo "typeName = ".$_POST["type"]."<br/>";

	echo "Category => json_decode(POST['typesArray']<br/>";
	print_r(json_decode($_POST['typesArray'], true));
	echo "<br/><br/><br/>";

	echo "Sub category => json_decode(POST['subTypeArray']<br/>";
	print_r(json_decode($_POST['subTypeArray'], true));
	echo "<br/><br/><br/>";

	echo "Products => json_decode(POST['productsArray']<br/>";
	print_r(json_decode($_POST['productsArray'], true));
	echo "<br/><br/><br/>";

	$objPHPExcel = new PHPExcel();
	
	$objPHPExcel->getProperties()->setCreator("Robert Kocjan")
								 ->setLastModifiedBy("Robert Kocjan")
								 ->setTitle("Sale by Department Raport");
	
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
		
				
		foreach($value as $k => $v){
			if($key == "Category"){
				$cat = $k;
			}
	
			$objPHPExcel->setActiveSheetIndex($index)->setCellValue('A'.$cellNo, $v['name']);
			$objPHPExcel->setActiveSheetIndex($index)->setCellValue('B'.$cellNo, $v['data'][$dt->getDates()['prevYear']['year']]);
			$objPHPExcel->setActiveSheetIndex($index)->setCellValue('C'.$cellNo, $v['data'][$dt->getDates()['currYear']['year']]);
			$objPHPExcel->setActiveSheetIndex($index)->setCellValue('D'.$cellNo, $v['growth']);
			$objPHPExcel->getActiveSheet()->getStyle('D'.$cellNo)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			
			$cellNo++;
		}
		
		$index++;
	}
	
	$objPHPExcel->setActiveSheetIndex(0);
	
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		
	$fileName .= '_'.$typeName.'_'.$dt->getCurrentYearDate()['dateEnd'].'.xlsx';
	
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
	
	echo $show;*/