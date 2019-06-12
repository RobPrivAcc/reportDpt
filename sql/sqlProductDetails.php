<?php
    include("../class/classCategories.php");
    include("../class/classDb.php");
    include("../class/classXML.php");
    include("../class/classDate.php");
    include("../class/classContent.php");
    

    $dt = new DATE();
    
    $xml = new xmlFile($_SERVER["DOCUMENT_ROOT"].'/dbXML.xml');
    
    $db = new dbConnection($xml->getConnectionArray());
	
    
    $catType = $_POST['type'];
	$catSubType = $_POST['subType'];
	$shopName = $_POST['shopName'];
	
    //$type = new product();
	$type = new CATEGORY();
	$type->openConnection($db->getDbConnectionByName($shopName));
    
	
	$content = new Content();

    $statsArray = array(); 
	$type->saleProductDetails($catType,$catSubType,$dt->getPreviousYearDate());
	$type->saleProductDetails($catType,$catSubType,$dt->getCurrentYearDate());
	
	
	$statsArray = $type->getSaleDetails();
	
	$content->createRow("","",false)
			->createCell("<h3>".$catType." > ".$catSubType."</h3>",12,' font-weight-bold')->setColors("","e89120");
	
	
	$content->createRow("","",false)
			->createCell('Product Name',6,' font-weight-bold')
			->createCell($dt->getYear()['lastYear'],2,' font-weight-bold')
			->createCell($dt->getYear()['currentYear'],2,' font-weight-bold')
			->createCell('Growth',2,' font-weight-bold');
	
	
	$productArray = array();
	
    foreach($statsArray as $key => $value){
		$lastYearStats = 0;
		$curentYearStats = 0;
		if(isset($value[$dt->getYear()['lastYear']])){
			$lastYearStats = $value[$dt->getYear()['lastYear']];
		}
		
		if(isset($value[$dt->getYear()['currentYear']])){
			$curentYearStats = $value[$dt->getYear()['currentYear']];
		}
		
		$content->createRow("","",true)
				->createCell($key,6)
				->createCell($lastYearStats,2)
				->createCell($curentYearStats,2)
				->createCell($type->growth($lastYearStats, $curentYearStats),2);


		$productArray[$key] = array($dt->getYear()['lastYear'] => $lastYearStats,
									$dt->getYear()['currentYear'] => $curentYearStats,
									'growth' => $type->growth($lastYearStats, $curentYearStats));
    }
	
	$headerArray = array($dt->getYear()['lastYear'],$dt->getYear()['currentYear'],'Growth');
	
	$content->getHidenInput('header',json_encode($headerArray));
	$content->getHidenInput('shop',$shopName);
	$content->getHidenInput('type',$catType);
	$content->getHidenInput('typesArray',$_POST['typesArray']);
	$content->getHidenInput('subTypesArray',$_POST['subTypeArray']);
	$content->getHidenInput('productsArray',json_encode($productArray));
	
	$content->showResult();
?>
