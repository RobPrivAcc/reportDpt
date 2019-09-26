<?php
session_start();
    include("../class/classCategories.php");
    include("../class/classDb.php");
    include("../class/classXML.php");
    include("../class/classDate.php");
    include("../class/classContent.php");
	include("../class/Growth.php");



    $dt = new DATE($_SESSION['dateFrom'],$_SESSION['dateTo']);

    
    $xml = new xmlFile($_SERVER["DOCUMENT_ROOT"].'/dbXML.xml');
    
    $db = new dbConnection($xml->getConnectionArray());


    $catType = $_POST['type'];
	$catSubType = $_POST['subType'];
	$shopName = $_POST['shopName'];


$typeStats = json_decode($_POST['typesArray']);


	$type = new CATEGORY($db->getDbConnectionByName($shopName));
	$type->setDateArray($dt->getDates());

    $statsArray = array();
	$saleArray = $type->saleTypeDetails($catType,$catSubType,true);


//	return;
//
foreach ($saleArray as $item => $value) {
	$gr = new Growth();
	$gr->setName($item);
	$gr->setDates($dt->getDates());

	foreach ($value as $year => $total) {
		$gr->setData($year,$total);
	}
	$gr->setGrowth();

	$statsArray[] = $gr;
	unset($gr);
}

	$content = new Content();
	$content->createCell("<h3>".$catType." > ".$catSubType."</h3>",12,' font-weight-bold')->setColors("","e89120")
			->createRow("","",false);
	
	
	$content->createCell('Product Name',6,' font-weight-bold')
			->createCell($dt->getYear()['lastYear'],2,' font-weight-bold')
			->createCell($dt->getYear()['currentYear'],2,' font-weight-bold')
			->createCell('Growth',2,' font-weight-bold')
			->createRow("","",false);


	$productArray = array();


    foreach($statsArray as $key => $value){

        $content->createCell($value->getName(),6)
				->createCell($value->getDataSet()[$value->getPrevYear()],2)
				->createCell($value->getDataSet()[$value->getCurrentYear()],2)
				->createCell($value->getGrowth(),2)
				->createRow("","",true);

/*
 *
 * */
		$productArray[] = array('name' => $value->getName(),
                                'data' => array($value->getPrevYear() => $value->getDataSet()[$value->getPrevYear()],
                                                $value->getCurrentYear() => $value->getDataSet()[$value->getCurrentYear()]),
                                'growth' => $value->getGrowth());

    }
	
	$headerArray = array($dt->getYear()['lastYear'],$dt->getYear()['currentYear'],'Growth');
	
	$content->getHidenInput('header',json_encode($headerArray));
	$content->getHidenInput('shop',$shopName);
	$content->getHidenInput('type',$catType);
	$content->getHidenInput('typesArray',$_POST['typesArray']);
	$content->getHidenInput('subTypesArray',$_POST['subTypeArray']);
	$content->getHidenInput('productsArray',json_encode($productArray));
	
	$content->showResult();

