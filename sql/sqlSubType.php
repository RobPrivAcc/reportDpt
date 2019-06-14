<?php

    include("../class/classCategories.php");
    include("../class/classDb.php");
    include("../class/classXML.php");
    include("../class/classDate.php");
    include("../class/classContent.php");
	
	
	$shopName = $_POST['shopName'];
	$type = $_POST['type'];
    
    $dt = new DATE();
 
	$content = new Content();
    
    
    $xml = new xmlFile($_SERVER["DOCUMENT_ROOT"].'/dbXML.xml');
    $db = new dbConnection($xml->getConnectionArray());
	
	$catType = new CATEGORY();
	$catType->openConnection($db->getDbConnectionByName($shopName));
	
	$catType->setDateArray($dt->getDates());
	
	$catType->saleSubTypeDetails($type);
	
	$subTypeArrayStats = $catType->getTypeArray();
	
    $content = new Content();
//	header
    $content->createRow("","",false)
			->createCell("<h3>".$type."</h3>",12,' font-weight-bold');
    
//  Categories
	$content->createCell('SubCategory',6,' font-weight-bold')
			->createCell($dt->getYear()['lastYear'],2,' font-weight-bold')
			->createCell($dt->getYear()['currentYear'],2,' font-weight-bold')
			->createCell('Growth',2,' font-weight-bold')
			->createRow("","",false);
			

//  Sub Categories
    foreach($subTypeArrayStats as $key => $value){
        $content->createCell($key,6,' subCatName')
				->createCell($value[$dt->getYear()['lastYear']],2,'')
				->createCell($value[$dt->getYear()['currentYear']],2,'')
				->createCell($value['growth'],2,'')
				->createRow(" selectedSubCatRow","",false);
    }
//	Hidden values
    $content->getHidenInput('shop',$shopName);
    $content->getHidenInput('type',$type);    
	$content->getHidenInput('typesArray',$_POST['typesArray']);
    $content->getHidenInput('subTypeArray',json_encode($subTypeArrayStats));    
    
    $content->showResult();

?>