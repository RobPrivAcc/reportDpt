<?php
var_dump($_POST);
/*
<?php
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
date_default_timezone_set('Europe/London');
ini_set('max_input_vars', 9000);

$shopsArray = json_decode($_POST['shops']);
$productsArray = json_decode($_POST['products']);
$shop = $_POST['shop'];

require_once dirname(__FILE__) . '/../classes/Excel/PHPExcel.php';

$objPHPExcel = new PHPExcel();

//$cellArray = array("A","B","C","D","E");

$objPHPExcel->getProperties()->setCreator("Robert Kocjan")
							 ->setLastModifiedBy("Robert Kocjan")
							 ->setTitle("PHPExcel Test Document")
							 ->setSubject("PHPExcel Test Document")
							 ->setDescription("Test document for PHPExcel, generated using PHP classes.")
							 ->setKeywords("office PHPExcel php")
							 ->setCategory("Test result file");
							 
	foreach($shopsArray as $index => $invRef){
		$cellNo = 1;
		$objPHPExcel->createSheet($index);
		$objPHPExcel->setActiveSheetIndex($index)
            ->setCellValue('A1', 'Product Name')
            ->setCellValue('B1', "Transfered")
				->setCellValue('C1', "Destination")
            ->setCellValue('D1', "Cost")
				->setCellValue('E1', "Value");
			
		$objPHPExcel->getActiveSheet()->getStyle('A1:E1')->getFont()->setBold(true);
		 
		$columnWidth = 12;
		 
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(80);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth($columnWidth+5);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth($columnWidth+15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth($columnWidth);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth($columnWidth);
		 
		 
		$objPHPExcel->getActiveSheet()->setTitle($invRef)->getStyle('A1:E1')->getAlignment()->setWrapText(TRUE);
		$objPHPExcel->getActiveSheet()->setAutoFilter($objPHPExcel->getActiveSheet()->calculateWorksheetDimension());
		$objPHPExcel->getActiveSheet()->getStyle('A1:E1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		
		foreach($productsArray as $productIndex => $productValue){
			//print_r($productValue);
			//echo $productValue->productName.'<br/>';
			if($invRef == $productValue->invRef){
				$cellNo++;
				$objPHPExcel->setActiveSheetIndex($index)->setCellValue('A'.$cellNo, $productValue->productName);
				$objPHPExcel->setActiveSheetIndex($index)->setCellValue('B'.$cellNo, $productValue->transfered);
				$objPHPExcel->setActiveSheetIndex($index)->setCellValue('C'.$cellNo, $productValue->invRef);
				$objPHPExcel->setActiveSheetIndex($index)->setCellValue('D'.$cellNo, $productValue->cost);
				$objPHPExcel->setActiveSheetIndex($index)->setCellValue('E'.$cellNo, $productValue->value);
				
			}
			
			
		}
		
			$objPHPExcel->getActiveSheet()->getStyle('F1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			$objPHPExcel->setActiveSheetIndex($index)->setCellValue('F1', 'Total');
			$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth($columnWidth);
			$objPHPExcel->getActiveSheet()->getStyle('G1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			$objPHPExcel->setActiveSheetIndex($index)->setCellValue('G1', '=sum(E2:E'.$cellNo.')');
			$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth($columnWidth);
			$objPHPExcel->getActiveSheet()->getStyle('F1:G1')->getFont()->setBold(true);
	}

$objPHPExcel->setActiveSheetIndex(0);

 if(count($productsArray) > 0){ 
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		
	$fileName = str_replace(" ","_",date("Y-m-d").'_'.$shop).'.xlsx';
	
	$fileName_to_save = $fileName;
	
	//$objWriter->save('../files/'.$fileName_to_save);
	
	$pathToFile = dirname(pathinfo(__FILE__)['dirname']).'\\files\\'.$fileName;
	$linkToFile = str_replace("\\","\\\\",$pathToFile);
	
		
	$objWriter->save('../files/'.$fileName_to_save);
	
	
	$directory = explode("\\",dirname(dirname(__FILE__)));
	
	$pathToFile = dirname(pathinfo(__FILE__)['dirname']).'\\files\\'.$fileName_to_save;
	
		if (file_exists($pathToFile)){
			$show = "Click to download <a href = '/".$directory[count($directory)-1]."/files/".$fileName_to_save."'>".$fileName_to_save."</a>";    
		}else{
			$show = "Ups.. something went wrong and file wasn't created. Contact Robert.";    
		}
	
	//if (file_exists($pathToFile)){
	//	$show = "<br/><div class='row'>";
	//		$show .= "<div class='col-xs-12 col-12'>";
	//			$show .= "<a href = '/transfer/files/".$fileName."'  class='btn btn-primary'><i class='fa fa-download' aria-hidden='true'></i>  Download <b>".$fileName."</b></a>";
	//		$show .= "</div>";
	//	$show .= "</div><br/>";
	//}else{
	//	 echo "Ups.. something went wrong and file wasn't created. Contact Robert.";    
	//}
 }else{
		$show = "<br/><div class='row'>";
			$show .= "<div class='col-xs-12 col-12'>";
				$show .= "No results found";
			$show .= "</div>";
		$show .= "</div><br/>";
}
	echo $show;
?>
*/
?>