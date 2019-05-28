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
	
    $div = "<div class = 'row'>";
		$div .= "<div class='col-xs-6 col-6 font-weight-bold'>Category</div>";
		$div .= "<div class='col-xs-2 col-2 font-weight-bold'>".$dt->getYear()['lastYear']."</div>";
		$div .= "<div class='col-xs-2 col-2 font-weight-bold'>".$dt->getYear()['currentYear']."</div>";
		$div .= "<div class='col-xs-2 col-2 font-weight-bold'>Growth</div>";
    $div .= "</div>";

	foreach($typeArray as $key => $value){
		$div .= "<div class = 'row selectedCatRow'>";
			$div .= "<div class='col-xs-6 col-6 catName'>".$key."</div>";
			$div .= "<div class='col-xs-2 col-2'>".$value[$dt->getYear()['lastYear']]."</div>";
			$div .= "<div class='col-xs-2 col-2'>".$value[$dt->getYear()['currentYear']]."</div>";
			$div .= "<div class='col-xs-2 col-2'>".$value["growth"]."</div>";
		$div .= "</div>";
	}

	$div .= "<input type = 'hidden' id= 'shop' value = '".$shopName."' />";
	$div .= "<input type = 'hidden' id= 'typesArray' value = '".json_encode($typeArray)."' />";

   echo $div;
?>