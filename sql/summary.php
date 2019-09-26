<?php
session_start();
include("../class/classCategories.php");
include("../class/classDb.php");
include("../class/classXML.php");
include("../class/classDate.php");
include("../class/classContent.php");
include("../class/Table.php");
include("../class/Types.php");


$_SESSION['dateFrom'] = $_POST['dateFrom'];
$_SESSION['dateTo'] = $_POST['dateTo'];
//$shopName = $_POST['shopName'];

//echo $_SESSION['dateFrom'].'  '.$_SESSION['dateTo'];


$date = new DATE($_SESSION['dateFrom'],$_SESSION['dateTo']);
$dateArray = $date->getDates();

$xml = new xmlFile($_SERVER["DOCUMENT_ROOT"].'/dbXML.xml');
$db = new dbConnection($xml->getConnectionArray());


$types = new Types($db->connect(2));
$types = $types->getTypes();


$tmp = null;


foreach ($db->getShopsName() as $k=>$shopName){

    $catType = new CATEGORY($db->getDbConnectionByName($shopName));
    $catType->setDateArray($dateArray);

    foreach ($types as $type=>$value){
        $tmp[$type][$shopName] = $catType->saleTypeDetails($type);
    }

}
$_SESSION['allShops'] = json_encode($tmp);
//var_dump($tmp);


$table = new Table();

$typeArray = array();
$table->addHeader("categories");
$table->addHeader($db->getShopsName())->makeUpper()->makeHeader(2);

$table->addHeader('');
$table->addHeader('');

foreach($db->getShopsName() as $k=>$shop){
    foreach($dateArray as $key=>$value){
        $table->addHeader($value['year'])->makeUpper();
    }
}

$table->makeHeader();

foreach ($tmp as $type => $shops){
    $table->addCell($type);
    $table->addCell('');
    foreach ($shops as $shopName => $years){
        foreach ($years as $year => $value){
            $table->addCell(round($value,0));
        }
    }
    $table->addRow();
}

//$table->addCell()




echo $table->getTable().'<hr/>';




















//$table = new Table();
//
//$typeArray = array();
//$table->addHeader("categories");
////$table->addHeader($shopsArray)->makeUpper();
//
////echo var_dump($dt->getDates());
//
//foreach ($shopsArray as $key=>$value){
//    $table->addHeader($value)->makeUpper();
//
////    $table->addCell($value)->addRow();
//
////    $catType = new CATEGORY($db->getDbConnectionByName($key));
////    //$catType->openConnection();
////
////    $catType->setDateArray($dt->getDates());
////    $catType -> setTypeSubtypeArray();
////    $catType->saleTypeDetails($dt->getCurrentYearDate());
////
////
////    $typeArray[$key] = $catType->getTypeArray();
//
//
//
//
//
//
//    $catType = new CATEGORY($db->getDbConnectionByName($value));
//    //$catType->openConnection();
//
//    $catType->setDateArray($dt->getDates());
//    $catType -> setTypeSubtypeArray();
//    $catType->saleTypeDetails($dt->getCurrentYearDate());
//
//
//    $typeArray[$value] = $catType->getTypeArray();
//var_dump($typeArray);
//}
//echo $table->getTable();
////var_dump($typeArray);
////
////
////$content = new Content();
//
////$content->createCell('Category',6,' font-weight-bold')
////    ->createCell($dt->getYear()['lastYear'],2,' font-weight-bold')
////    ->createCell($dt->getYear()['currentYear'],2,' font-weight-bold')
////    ->createCell('Growth',2,' font-weight-bold')
////    ->createRow("","",false);
////
////$content->createCell('Category',6,' font-weight-bold')
////    ->createCell($dt->getYear()['lastYear'],2,' font-weight-bold')
////    ->createCell($dt->getYear()['currentYear'],2,' font-weight-bold')
////    ->createCell('Growth',2,' font-weight-bold')
////    ->createRow("","",false);
//
//
////foreach($typeArray as $key => $value){
////    $content-> createCell($key,6,' catName')
////        -> createCell($value[$dt->getYear()['lastYear']],2,'')
////        -> createCell($value[$dt->getYear()['currentYear']],2,'')
////        -> createCell($value["growth"],2,'')
////        -> createRow(" selectedCatRow","",false);
////}
////
////$content->getHidenInput('shop',$shopName);
////$content->getHidenInput('typesArray',json_encode($typeArray));
////
////$content->showResult();
//
