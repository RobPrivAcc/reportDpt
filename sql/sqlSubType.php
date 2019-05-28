<?php
    include("../class/classCategories.php");
    include("../class/classDb.php");
    include("../class/classXML.php");
    include("../class/classDate.php");
    include("../class/classContent.php");
	
	
	$shopName = $_POST['shopName'];
	$type = $_POST['type'];

    $dt = new DATE();
         
    $xml = new xmlFile($_SERVER["DOCUMENT_ROOT"].'/dbXML.xml');
    $db = new dbConnection($xml->getConnectionArray());
	
	$catType = new CATEGORY();
	$catType->openConnection($db->getDbConnectionByName($shopName));
	
	$catType->setDateArray($dt->getDates());
	
	$catType->saleSubTypeDetails($type);
	
	$subTypeArrayStats = $catType->getTypeArray();
	
    $div = "<div class = 'row'>";
    $div .= "<div class='col-xs-12 col-12'><h3>".$type."</h3></div>";
    $div .= "</div>";
//    
    $div .= "<div class = 'row'>";
		$div .= "<div class='col-xs-6 col-6 font-weight-bold'>SubCategory</div>";
		$div .= "<div class='col-xs-2 col-2 font-weight-bold'>".$dt->getYear()['lastYear']."</div>";
		$div .= "<div class='col-xs-2 col-2 font-weight-bold'>".$dt->getYear()['currentYear']."</div>";
		$div .= "<div class='col-xs-2 col-2 font-weight-bold'>Growth</div>";
    $div .= "</div>";
//    
    foreach($subTypeArrayStats as $key => $value){
        $div .= "<div class = 'row selectedSubCatRow'>";
            $div .= "<div class='col-xs-6 col-6 subCatName'>". $key."</div>";
			$div .= "<div class='col-xs-2 col-2'>".$value[$dt->getYear()['lastYear']]."</div>";
			$div .= "<div class='col-xs-2 col-2'>".$value[$dt->getYear()['currentYear']]."</div>";
			$div .= "<div class='col-xs-2 col-2'>".$value['growth']."</div>";
        $div .= "</div>";
    }
	
    $div .= "<input type = 'hidden' id= 'shop' value = '".$shopName."' />";
    $div .= "<input type = 'hidden' id= 'type' value = '".$type."' />";
	$div .= "<input type = 'hidden' id= 'typesArray' value = '".$_POST['typesArray']."' />";
	$div .= "<input type = 'hidden' id= 'subTypeArray' value = '".json_encode($subTypeArrayStats)."' />";
   
    echo $div;    
?>