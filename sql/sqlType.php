<?php
session_start();
    include("../class/classCategories.php");
    include("../class/classDb.php");
    include("../class/classXML.php");
    include("../class/classDate.php");
    include("../class/classContent.php");
    include("../class/Types.php");
    include("../class/Growth.php");

    $typeArray = array();

	$_SESSION['dateFrom'] = (isset($_POST['dateFrom']) ? $_POST['dateFrom'] : '');
	$_SESSION['dateTo'] = (isset($_POST['dateTo']) ? $_POST['dateTo'] : '');


    $shopName = $_POST['shopName'];


    $dt = new DATE($_SESSION['dateFrom'],$_SESSION['dateTo']);

    $xml = new xmlFile($_SERVER["DOCUMENT_ROOT"].'/dbXML.xml');
    $db = new dbConnection($xml->getConnectionArray());
	
	$catType = new CATEGORY($db->getDbConnectionByName($shopName));

	
	$catType->setDateArray($dt->getDates());

    $types = new Types($db->connect(2));
    $types = $types->getTypes();

    foreach ($types as $type => $subType){
//        $typeArray[$type] = $catType->saleTypeDetails($type);
        $gr = new Growth();
        $gr->setName($type);
        $gr->setDates($dt->getDates());

        foreach ($catType->saleTypeDetails($type) as $year => $total) {
            $gr->setData($year,$total);
        }
        $gr->setGrowth();

        $typeArray[] = $gr;
        unset($gr);
    }

	$content = new Content();

	$content->createCell('Category',6,' font-weight-bold')
			->createCell($dt->getYear()['lastYear'],2,' font-weight-bold')
			->createCell($dt->getYear()['currentYear'],2,' font-weight-bold')
			->createCell('Growth',2,' font-weight-bold')
			->createRow("","",false);
	
	foreach($typeArray as $key => $value){
		$content-> createCell($value->getName(),6,' catName')
				 -> createCell($value->getDataSet()[$value->getPrevYear()],2,'')
				 -> createCell($value->getDataSet()[$value->getCurrentYear()],2,'')
				 -> createCell($value->getGrowth(),2,'')
				 -> createRow(" selectedCatRow","",false);
	}

	$content->getHidenInput('shop',$shopName);
	$content->getHidenInput('typesArray',json_encode($typeArray));
	
	$content->showResult(true);