<?php
    include("../class/classProduct.php");
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
	
    $type = new product();
	$type->openConnection($db->getDbConnectionByName($shopName));
    
	

    $statsArray = array(); 
	$type->saleProductDetails($catType,$catSubType,$dt->getPreviousYearDate());
	$type->saleProductDetails($catType,$catSubType,$dt->getCurrentYearDate());
	
	$statsArray = $type->getSaleDetails();

	
    $div = "<div class = 'row'>";
    $div .= "<div class='col-xs-12 col-12'><h3>".$catType." > ".$catSubType."</h3></div>";
    $div .= "</div>";
    //
    $div .= "<div class = 'row'>";
    $div .= "<div class='col-xs-6 col-6 font-weight-bold'>Product Name</div><div class='col-xs-2 col-2 font-weight-bold'>".$dt->getYear()['lastYear']."</div><div class='col-xs-2 col-2 font-weight-bold'>".$dt->getYear()['currentYear']."</div><div class='col-xs-2 col-2 font-weight-bold'>Growth</div></div>";
    $div .= "</div>";
    //
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
		
		
        $div .= "<div class = 'row selectedRow'>";
            $div .= "<div class='col-xs-6 col-6'>". $key."</div><div class='col-xs-2 col-2'>".$lastYearStats."</div><div class='col-xs-2 col-2'>".$curentYearStats."</div><div class='col-xs-2 col-2'>".$type->growth($lastYearStats, $curentYearStats)."</div>";
        $div .= "</div>";
		$value = null;
		$productArray[$key] = array($dt->getYear()['lastYear'] => $lastYearStats,
									$dt->getYear()['currentYear'] => $curentYearStats,
									'growth' => $type->growth($lastYearStats, $curentYearStats));
    }
	
	$headerArray = array($dt->getYear()['lastYear'],$dt->getYear()['currentYear'],'Growth');

	$div .= "<input type = 'hidden' id= 'header' value = '".json_encode($headerArray)."' />";
    $div .= "<input type = 'hidden' id= 'shop' value = '".$shopName."' />";
    $div .= "<input type = 'hidden' id= 'type' value = '".$catType."' />";
	$div .= "<input type = 'hidden' id= 'typesArray' value = '".$_POST['typesArray']."' />";
	$div .= "<input type = 'hidden' id= 'subTypesArray' value = '".$_POST['subTypeArray']."' />";
	$div .= "<input type = 'hidden' id= 'productsArray' value = '".json_encode($productArray)."' />";
	
	echo $div;
?>
