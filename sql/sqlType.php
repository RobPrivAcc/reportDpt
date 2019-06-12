<?php
    include("../class/classCategories.php");
    include("../class/classDb.php");
    include("../class/classXML.php");
    include("../class/classDate.php");
    include("../class/classContent.php");
    
    $shopName = $_POST['shopName'];
    
    $dt = new DATE();
	
    $xml = new xmlFile($_SERVER["DOCUMENT_ROOT"].'/dbXML.xml');
    $db = new dbConnection($xml->getConnectionArray());
	
	$catType = new CATEGORY();
	$catType->openConnection($db->getDbConnectionByName($shopName));
	
	$catType->setDateArray($dt->getDates());
	
    $catType->saleTypeDetails($dt->getCurrentYearDate());
    
	
	$typeArray = $catType->getTypeArray();
	
	$content = new Content();

	$content->createRow("","",false)
			->createCell('Category',6,' font-weight-bold')
			->createCell($dt->getYear()['lastYear'],2,' font-weight-bold')
			->createCell($dt->getYear()['currentYear'],2,' font-weight-bold')
			->createCell('Growth',2,' font-weight-bold');
	
	foreach($typeArray as $key => $value){
		$content -> createRow(" selectedCatRow","",false)
				 -> createCell($key,6,' catName')
				 -> createCell($value[$dt->getYear()['lastYear']],2,'')
				 -> createCell($value[$dt->getYear()['currentYear']],2,'')
				 -> createCell($value["growth"],2,'');
	}
	
	$content->getHidenInput('shop',$shopName);
	$content->getHidenInput('typesArray',json_encode($typeArray));
	
	$content->showResult();
	
?>