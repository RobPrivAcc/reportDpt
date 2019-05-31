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
	
	$content->createCell("<h3>".$catType." > ".$catSubType."</h3>",12);
	
	$content->createRow("","",false);
	
    //$div = "<div class = 'row'>";
    //$div .= "<div class='col-xs-12 col-12'><h3>".$catType." > ".$catSubType."</h3></div>";
    //$div .= "</div>";
    //
	$content->createCell('Product Name',6,' font-weight-bold');
	$content->createCell($dt->getYear()['lastYear'],2,' font-weight-bold');
	$content->createCell($dt->getYear()['currentYear'],2,' font-weight-bold');
	$content->createCell('Growth',2,' font-weight-bold');
	$content->createRow("","",false);
	
	
	
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
		
		$content->createCell($key,6);
		$content->createCell($lastYearStats,2);
		$content->createCell($curentYearStats,2);
		$content->createCell($type->growth($lastYearStats, $curentYearStats),2);
		$content->createRow("","",true);

        //$div .= "<div class = 'row selectedRow'>";
        //    $div .= "<div class='col-xs-6 col-6'>". $key."</div><div class='col-xs-2 col-2'>".$lastYearStats."</div><div class='col-xs-2 col-2'>".$curentYearStats."</div><div class='col-xs-2 col-2'>".$type->growth($lastYearStats, $curentYearStats)."</div>";
        //$div .= "</div>";
		$value = null;
		$productArray[$key] = array($dt->getYear()['lastYear'] => $lastYearStats,
									$dt->getYear()['currentYear'] => $curentYearStats,
									'growth' => $type->growth($lastYearStats, $curentYearStats));
    }
	
	//echo $content->showResult();
	
	$headerArray = array($dt->getYear()['lastYear'],$dt->getYear()['currentYear'],'Growth');
	
	$content->getHidenInput('header',json_encode($headerArray));
	$content->getHidenInput('shop',$shopName);
	$content->getHidenInput('type',$catType);
	$content->getHidenInput('typesArray',$_POST['typesArray']);
	$content->getHidenInput('subTypesArray',$_POST['subTypeArray']);
	$content->getHidenInput('productsArray',json_encode($productArray));
	
	echo $content->showResult();
	
//	$div .= "<input type = 'hidden' id= 'header' value = '".json_encode($headerArray)."' />";
//    $div .= "<input type = 'hidden' id= 'shop' value = '".$shopName."' />";
//    $div .= "<input type = 'hidden' id= 'type' value = '".$catType."' />";
//	$div .= "<input type = 'hidden' id= 'typesArray' value = '".$_POST['typesArray']."' />";
//	$div .= "<input type = 'hidden' id= 'subTypesArray' value = '".$_POST['subTypeArray']."' />";
//	$div .= "<input type = 'hidden' id= 'productsArray' value = '".json_encode($productArray)."' />";
//	
//	echo $div;
?>
