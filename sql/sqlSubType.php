<?php
session_start();
    include('../class/classCategories.php');
    include('../class/classDb.php');
    include('../class/classXML.php');
    include('../class/classDate.php');
    include('../class/classContent.php');
    include('../class/Types.php');
    include('../class/Growth.php');


	$shopName = $_POST['shopName'];
	$type = $_POST['type'];
    $typesArray = $_POST['typesArray'];

    $typeArray = array();

    $dt = new DATE($_SESSION['dateFrom'],$_SESSION['dateTo']);

	$content = new Content();

    $xml = new xmlFile($_SERVER["DOCUMENT_ROOT"].'/dbXML.xml');

    $db = new dbConnection($xml->getConnectionArray());
	
	$catType = new CATEGORY($db->getDbConnectionByName($shopName));

	$catType->setDateArray($dt->getDates());

    $types = new Types($db->connect(2));
    $types = $types->getTypes();

    foreach($types[$type] as $key => $val){
//        $typeArray[$val] = $catType->saleTypeDetails($type,$val);

        $gr = new Growth();
        $gr->setName($val);
        $gr->setDates($dt->getDates());

        foreach ($catType->saleTypeDetails($type,$val) as $year => $total) {
            $gr->setData($year,$total);
        }
        $gr->setGrowth();

        $typeArray[] = $gr;
        unset($gr);
    }



	
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
			

	/*
	 * 		$content-> createCell($value->getName(),6,' catName')
				 -> createCell($value->getDataSet()[$value->getPrevYear()],2,'')
				 -> createCell($value->getDataSet()[$value->getCurrentYear()],2,'')
				 -> createCell($value->getGrowth(),2,'')
				 -> createRow(" selectedCatRow","",false);
	 * */

//  Sub Categories
    foreach($typeArray as $key => $value){
        $content->createCell($value->getName(),6,' subCatName')
				->createCell($value->getDataSet()[$value->getPrevYear()],2,'')
				->createCell($value->getDataSet()[$value->getCurrentYear()],2,'')
				->createCell($value->getGrowth(),2,'')
				->createRow(" selectedSubCatRow","",false);
    }
//	Hidden values
    $content->getHidenInput('shop',$shopName);
    $content->getHidenInput('type',$type);    
	$content->getHidenInput('typesArray',$_POST['typesArray']);
    $content->getHidenInput('subTypeArray',json_encode($typeArray));
    
    $content->showResult(true);